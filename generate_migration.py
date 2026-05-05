"""
Migration script: optical-old → optical-new schema
Generates a ready-to-import SQL file for phpMyAdmin
"""
import re

# ─── Load both SQL files ───────────────────────────────────────────────────────
with open('C:/laragon/www/optics/Project/optical-old.sql', 'r', encoding='utf-8', errors='ignore') as f:
    OLD = f.read()

with open('C:/laragon/www/optics/Project/optical-new.sql', 'r', encoding='utf-8', errors='ignore') as f:
    NEW = f.read()

# ─── Helpers ──────────────────────────────────────────────────────────────────

def get_table_cols(sql, table):
    """Return ordered list of column names from CREATE TABLE."""
    pattern = r'CREATE TABLE `' + table + r'`\s*\((.*?)\)\s*ENGINE'
    m = re.search(pattern, sql, re.DOTALL)
    if not m:
        return []
    cols = []
    for line in m.group(1).split('\n'):
        line = line.strip().strip(',')
        col_m = re.match(r'`(\w+)`', line)
        if col_m:
            up = line.upper()
            if not any(up.startswith(k) for k in ('PRIMARY', 'KEY ', 'UNIQUE', 'CONSTRAINT', 'INDEX')):
                cols.append(col_m.group(1))
    return cols

def parse_insert(insert_sql):
    """
    Parse an INSERT INTO statement.
    Returns (table_name, [col_names], [[row_values_as_raw_strings]])
    """
    tm = re.match(r"INSERT INTO `(\w+)`", insert_sql)
    table = tm.group(1) if tm else ''

    col_m = re.search(r'\((`[^)]+`)\)', insert_sql)
    if not col_m:
        return table, [], []
    cols = [c.strip().strip('`') for c in re.split(r'`,\s*`', col_m.group(1).strip('`'))]

    vals_start = insert_sql.index('VALUES') + 6
    s = insert_sql[vals_start:].strip().rstrip(';')

    rows = []
    i = 0
    while i < len(s):
        if s[i] == '(':
            i += 1
            row_vals, current, in_quote, qc = [], '', False, None
            while i < len(s):
                c = s[i]
                if in_quote:
                    if c == '\\' and i + 1 < len(s):
                        current += c + s[i + 1]; i += 2; continue
                    elif c == qc:
                        current += c; in_quote = False
                    else:
                        current += c
                elif c in ("'", '"'):
                    in_quote = True; qc = c; current += c
                elif c == ',':
                    row_vals.append(current.strip()); current = ''
                elif c == ')':
                    row_vals.append(current.strip())
                    rows.append(row_vals); i += 1; break
                else:
                    current += c
                i += 1
        else:
            i += 1
    return table, cols, rows

def build_insert(table, cols, rows, batch=500):
    """Render rows as INSERT SQL."""
    if not rows:
        return ''
    col_str = ', '.join(f'`{c}`' for c in cols)
    out = []
    for b in range(0, len(rows), batch):
        chunk = rows[b:b + batch]
        out.append(
            f"INSERT INTO `{table}` ({col_str}) VALUES\n" +
            ',\n'.join('(' + ', '.join(r) + ')' for r in chunk) + ';'
        )
    return '\n'.join(out)

def extract_insert_blocks(sql, table):
    """
    Extract all INSERT blocks for a table, correctly ignoring semicolons inside quoted strings.
    Handles cases like description = 'O; something' without breaking.
    """
    results = []
    search_str = f'INSERT INTO `{table}`'
    pos = 0
    while True:
        start = sql.find(search_str, pos)
        if start == -1:
            break
        i = start
        in_quote = False
        qc = None
        end = -1
        while i < len(sql):
            c = sql[i]
            if in_quote:
                if c == '\\' and i + 1 < len(sql):
                    i += 2
                    continue
                elif c == qc:
                    in_quote = False
            elif c in ("'", '"'):
                in_quote = True
                qc = c
            elif c == ';' and not in_quote:
                end = i + 1
                break
            i += 1
        if end == -1:
            break
        results.append(sql[start:end])
        pos = end
    return results

def migrate_data(table, col_rename=None, new_col_defaults=None, skip_old=None):
    """
    Read INSERT rows from OLD, remap columns to NEW schema, return SQL string.
    col_rename   : {old_col_name: new_col_name}
    new_col_defaults : {new_col_name: 'SQL_value_string'}
    skip_old     : [old_col_names_to_ignore]
    """
    col_rename       = col_rename or {}
    new_col_defaults = new_col_defaults or {}
    skip_old         = skip_old or []

    new_cols = get_table_cols(NEW, table)
    if not new_cols:
        return f'-- WARNING: `{table}` not in new schema\n'

    raw_inserts = extract_insert_blocks(OLD, table)
    if not raw_inserts:
        return f'-- No data in old DB for `{table}`\n'

    all_rows = []
    for ins in raw_inserts:
        _, old_cols, rows = parse_insert(ins)
        for row in rows:
            # build a name→value map from old row
            old_map = {}
            for idx, col in enumerate(old_cols):
                if col in skip_old:
                    continue
                mapped = col_rename.get(col, col)
                old_map[mapped] = row[idx] if idx < len(row) else 'NULL'

            new_row = []
            for col in new_cols:
                if col in old_map:
                    new_row.append(old_map[col])
                elif col in new_col_defaults:
                    new_row.append(new_col_defaults[col])
                else:
                    new_row.append('NULL')
            all_rows.append(new_row)

    return build_insert(table, new_cols, all_rows)

def keep_from_new(table):
    """Return INSERT statements from NEW DB as-is."""
    blocks = extract_insert_blocks(NEW, table)
    return '\n'.join(blocks) if blocks else f'-- No data in new DB for `{table}`\n'

def extract_schema_new():
    """Extract all CREATE TABLE + ALTER TABLE blocks from new SQL."""
    # Get everything between the first CREATE TABLE and end (structure only)
    # We'll grab the full DDL section
    creates = re.findall(r'(CREATE TABLE `\w+`.*?;\n)', NEW, re.DOTALL)
    alters  = re.findall(r'(ALTER TABLE `\w+`[^;]+;)', NEW, re.DOTALL)
    return creates, alters

# ─── Get max user id from old to assign new superuser id ──────────────────────
user_blocks = extract_insert_blocks(OLD, 'users')
_, _, old_users_rows = parse_insert(user_blocks[0]) if user_blocks else ('', [], [])
old_user_ids = [int(r[0]) for r in old_users_rows if r[0].isdigit()]
new_super_id = max(old_user_ids) + 1
print(f'New superuser ID will be: {new_super_id}')

# ─── Get old role_user rows for model_has_roles mapping ───────────────────────
ru_inserts = extract_insert_blocks(OLD, 'role_user')
_, _, ru_rows = parse_insert(ru_inserts[0]) if ru_inserts else ('', [], [])

# Map old role_id → new Spatie role_id
# Old: 1=super_admin → New: 1=super-admin
# Old: 2=admin       → New: 6=admin
old_to_new_role = {'1': '1', '2': '6'}

mhr_rows = []  # model_has_roles rows
seen_mhr = set()
for row in ru_rows:
    old_rid, uid = row[0], row[1]
    new_rid = old_to_new_role.get(old_rid, '1')
    key = (new_rid, uid)
    if key not in seen_mhr:
        mhr_rows.append([new_rid, "'App\\\\User'", uid])
        seen_mhr.add(key)

# Add new superuser to model_has_roles
mhr_rows.append(['1', "'App\\\\User'", str(new_super_id)])

mhr_sql = build_insert('model_has_roles', ['role_id', 'model_type', 'model_id'], mhr_rows)

# ─── New superuser password hash (Admin@12345) ────────────────────────────────
NEW_ADMIN_HASH = '$2y$10$WeJxrc7guftiWWJeENmqserj9Yohb/tyRK3ZUOlVov1PvOhNoKSHO'
NOW = '2026-05-04 00:00:00'

new_user_sql = (
    f"INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `image`, "
    f"`email_verified_at`, `password`, `remember_token`, `branch_id`, `is_active`, "
    f"`last_login_at`, `last_login_ip`, `salary`, `commission`, `created_at`, `updated_at`) VALUES\n"
    f"({new_super_id}, 'System', 'Admin', 'system.admin@optics.com', 'default.png', "
    f"NULL, '{NEW_ADMIN_HASH}', NULL, NULL, 1, NULL, NULL, NULL, NULL, '{NOW}', '{NOW}');"
)

# ─── Build final SQL ──────────────────────────────────────────────────────────
creates, alters = extract_schema_new()

lines = []
lines.append("-- =====================================================================")
lines.append("-- optical-migrated.sql")
lines.append("-- Generated: 2026-05-04")
lines.append("-- Source data : optical-old.sql")
lines.append("-- Target schema: optical-new.sql")
lines.append("-- New superuser: system.admin@optics.com / Admin@12345")
lines.append("-- =====================================================================")
lines.append("")
lines.append("SET FOREIGN_KEY_CHECKS=0;")
lines.append("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';")
lines.append("SET NAMES utf8mb4;")
lines.append("")

# ── 1. CREATE TABLE (all from new) ────────────────────────────────────────────
lines.append("-- ─── SCHEMA ─────────────────────────────────────────────────────────")
for c in creates:
    lines.append(c)

# ── 2. ALTER TABLE / Constraints ──────────────────────────────────────────────
lines.append("-- ─── CONSTRAINTS ────────────────────────────────────────────────────")
for a in alters:
    lines.append(a)
    lines.append("")

# ── 3. Seed tables from NEW ───────────────────────────────────────────────────
lines.append("")
lines.append("-- ─── SEED DATA FROM NEW DB (roles, permissions, settings…) ─────────")

for t in ['roles', 'permissions', 'role_has_permissions', 'permission_role',
          'permission_user', 'settings', 'expense_categories', 'migrations']:
    lines.append(f"\n-- {t}")
    lines.append(keep_from_new(t))

# ── 4. Branches (rename cols) ─────────────────────────────────────────────────
lines.append("\n-- ─── OPERATIONAL DATA FROM OLD DB ──────────────────────────────────")

lines.append("\n-- branches")
lines.append(migrate_data(
    'branches',
    col_rename   = {'branch_name': 'name', 'branch_id': 'code'},
    new_col_defaults = {
        'name_ar': 'NULL', 'city': 'NULL', 'phone': 'NULL', 'email': 'NULL',
        'manager_name': 'NULL', 'description': 'NULL',
        'is_active': '1', 'rent_amount': "'0.00'", 'is_main': '0',
        'opening_time': 'NULL', 'closing_time': 'NULL',
        'total_sales': "'0.00'", 'total_invoices': '0', 'deleted_at': 'NULL',
        # 'address' must be NOT NULL — use name as placeholder
        'address': 'NULL',   # will be overridden below
    },
    skip_old = []
))

# The 'address' col is NOT NULL. We need to copy branch_name into it.
# Re-do branches with custom logic for address:
def migrate_branches():
    new_cols = get_table_cols(NEW, 'branches')
    raw = extract_insert_blocks(OLD, 'branches')
    all_rows = []
    for ins in raw:
        _, old_cols, rows = parse_insert(ins)
        for row in rows:
            om = {col: (row[i] if i < len(row) else 'NULL') for i, col in enumerate(old_cols)}
            nr = []
            for col in new_cols:
                if col == 'name':
                    nr.append(om.get('branch_name', 'NULL'))
                elif col == 'name_ar':
                    nr.append('NULL')
                elif col == 'code':
                    nr.append(om.get('branch_id', 'NULL'))
                elif col == 'address':
                    # Use branch_name as address placeholder (NOT NULL requirement)
                    nr.append(om.get('branch_name', "'Branch'"))
                elif col == 'id':
                    nr.append(om.get('id', 'NULL'))
                elif col in ('created_at', 'updated_at'):
                    nr.append(om.get(col, 'NULL'))
                elif col == 'is_active':
                    nr.append('1')
                elif col == 'is_main':
                    nr.append('0')
                elif col in ('rent_amount', 'total_sales'):
                    nr.append("'0.00'")
                elif col == 'total_invoices':
                    nr.append('0')
                else:
                    nr.append('NULL')
            all_rows.append(nr)
    return build_insert('branches', new_cols, all_rows)

# Replace the placeholder branches insert we added above
# (We'll just do it properly from the start)
# Remove last appended line and redo:
lines.pop()  # remove the bad branches insert
lines.append(migrate_branches())

# ── 5. Simple same-schema tables ─────────────────────────────────────────────
for t in ['brands', 'categories', 'doctors', 'glass_models']:
    lines.append(f"\n-- {t}")
    lines.append(migrate_data(t))

# ── 6. customers ─────────────────────────────────────────────────────────────
lines.append("\n-- customers")
lines.append(migrate_data('customers'))

# ── 7. users (from old + new superuser) ──────────────────────────────────────
lines.append("\n-- users (from old)")
lines.append(migrate_data('users', new_col_defaults={
    'branch_id': 'NULL', 'is_active': '1',
    'last_login_at': 'NULL', 'last_login_ip': 'NULL',
    'salary': 'NULL', 'commission': 'NULL',
}))
lines.append("\n-- NEW superuser (email: system.admin@optics.com / pass: Admin@12345)")
lines.append(new_user_sql)

# ── 8. model_has_roles ────────────────────────────────────────────────────────
lines.append("\n-- model_has_roles (mapped from old role_user + new superuser)")
lines.append(mhr_sql)

# ── 9. products ───────────────────────────────────────────────────────────────
lines.append("\n-- products")
lines.append(migrate_data('products', new_col_defaults={
    'barcode': 'NULL', 'is_active': '1'
}))

# ── 10. glass_lenses ──────────────────────────────────────────────────────────
lines.append("\n-- glass_lenses")
lines.append(migrate_data('glass_lenses', new_col_defaults={
    'branch_id': 'NULL', 'lens_brand_id': 'NULL'
}))

# ── 11. lenses (eye prescriptions) ───────────────────────────────────────────
lines.append("\n-- lenses")
lines.append(migrate_data('lenses', new_col_defaults={
    'attachment': 'NULL'
}))

# ── 12. invoices ──────────────────────────────────────────────────────────────
lines.append("\n-- invoices")
lines.append(migrate_data('invoices', new_col_defaults={
    'branch_id': 'NULL',
    'total_before_discount': 'NULL',
    'canceled_at': 'NULL', 'canceled_by': 'NULL',
    'delivered_at': 'NULL', 'delivered_by': 'NULL',
    'insurance_approval_amount': 'NULL',
    'insurance_cardholder_type': 'NULL',
    'insurance_cardholder_type_id': 'NULL',
}))

# ── 13. invoice_items ─────────────────────────────────────────────────────────
lines.append("\n-- invoice_items")
lines.append(migrate_data('invoice_items', new_col_defaults={
    'is_delivered': '0',
    'delivered_at': 'NULL',
    'direction': 'NULL',
    'insurance_cardholder_discount': 'NULL',
}))

# ── 14. payments ──────────────────────────────────────────────────────────────
lines.append("\n-- payments")
lines.append(migrate_data('payments', new_col_defaults={
    'is_refund': '0'
}))

# ── 15. password_resets ───────────────────────────────────────────────────────
lines.append("\n-- password_resets")
lines.append(migrate_data('password_resets'))

# ── 16. Re-enable FK ─────────────────────────────────────────────────────────
lines.append("\n")
lines.append("SET FOREIGN_KEY_CHECKS=1;")
lines.append("")
lines.append("-- =====================================================================")
lines.append("-- Import complete.")
lines.append(f"-- New superuser: system.admin@optics.com / Admin@12345")
lines.append("-- =====================================================================")

# ─── Write output ─────────────────────────────────────────────────────────────
output_path = 'C:/laragon/www/optics/Project/optical-migrated.sql'
with open(output_path, 'w', encoding='utf-8') as f:
    f.write('\n'.join(lines))

print(f'\nDone! Output saved to: {output_path}')
print(f'New superuser ID: {new_super_id}')
print(f'model_has_roles rows generated: {len(mhr_rows)}')
