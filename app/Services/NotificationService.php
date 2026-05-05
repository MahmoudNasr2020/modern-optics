<?php

namespace App\Services;

use App\Notification;
use App\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * إنشاء إشعار جديد
     */
    public static function create(array $data)
    {
        return Notification::create([
            'user_id' => $data['user_id'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'role' => $data['role'] ?? null,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? null,
            'created_by' => Auth::id(),
            'icon' => $data['icon'] ?? 'bell',
            'color' => $data['color'] ?? 'info',
            'action_url' => $data['action_url'] ?? null,
        ]);
    }

    /**
     * إشعار لمستخدم معين
     */
    public static function notifyUser($userId, array $data)
    {
        return self::create(array_merge($data, ['user_id' => $userId]));
    }

    /**
     * إشعار لفرع معين
     */
    public static function notifyBranch($branchId, array $data)
    {
        return self::create(array_merge($data, ['branch_id' => $branchId]));
    }

    /**
     * إشعار لدور معين
     */
    public static function notifyRole($role, array $data)
    {
        return self::create(array_merge($data, ['role' => $role]));
    }

    /**
     * إشعار لكل مستخدمي فرع
     */
    public static function notifyBranchUsers($branchId, array $data)
    {
        $users = User::where('branch_id', $branchId)->get();

        foreach ($users as $user) {
            self::notifyUser($user->id, $data);
        }
    }

    /**
     * إشعار لكل المديرين
     */
    public static function notifyManagers(array $data)
    {
        return self::notifyRole('manager', $data);
    }

    /**
     * إشعار لكل السوبر أدمن
     */
    public static function notifySuperAdmins(array $data)
    {
        return self::notifyRole('super-admin', $data);
    }

    /**
     * ✅ الحصول على إشعارات المستخدم الحالي
     * (بدون الإشعارات اللي هو عملها - بدون استثناءات!)
     */
    public static function getUserNotifications($limit = 10)
    {
        $user  = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        return Notification::where(function ($query) use ($user, $roles) {
            // 1. Notifications targeted directly at this user
            $query->where('user_id', $user->id);

            // 2. Branch notifications
            if ($user->branch_id) {
                // Regular employee → only their branch
                $query->orWhere('branch_id', $user->branch_id);
            } else {
                // No branch assigned (super-admin / manager) → see ALL branch notifications
                $query->orWhereNotNull('branch_id');
            }

            // 3. Role-based notifications
            if (!empty($roles)) {
                $query->orWhereIn('role', $roles);
            }

            // 4. General broadcast notifications (no specific target)
            $query->orWhere(function ($q) {
                $q->whereNull('user_id')
                  ->whereNull('branch_id')
                  ->whereNull('role');
            });
        })
        ->where(function ($q) use ($user) {
            // Never show the user their own notifications
            $q->whereNull('created_by')
              ->orWhere('created_by', '!=', $user->id);
        })
        ->latest()
        ->limit($limit)
        ->get();
    }

    /**
     * ✅ عدد الإشعارات غير المقروءة
     * (بدون الإشعارات اللي هو عملها)
     */
    public static function getUnreadCount()
    {
        $user  = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        return Notification::where(function ($query) use ($user, $roles) {
            $query->where('user_id', $user->id);

            if ($user->branch_id) {
                $query->orWhere('branch_id', $user->branch_id);
            } else {
                $query->orWhereNotNull('branch_id');
            }

            if (!empty($roles)) {
                $query->orWhereIn('role', $roles);
            }

            $query->orWhere(function ($q) {
                $q->whereNull('user_id')
                  ->whereNull('branch_id')
                  ->whereNull('role');
            });
        })
        ->where(function ($q) use ($user) {
            $q->whereNull('created_by')
              ->orWhere('created_by', '!=', $user->id);
        })
        ->where('is_read', false)
        ->count();
    }

    /**
     * ✅ تحديد الكل كمقروء
     * (بدون الإشعارات اللي هو عملها)
     */
    public static function markAllAsRead()
    {
        $user = Auth::user();

        Notification::where(function($query) use ($user) {
            // 1. إشعارات موجهة للمستخدم مباشرة
            $query->where('user_id', $user->id);

            // 2. إشعارات موجهة للفرع
            if ($user->branch_id) {
                $query->orWhere('branch_id', $user->branch_id);
            }

            // 3. إشعارات موجهة للأدوار
            $roles = $user->getRoleNames()->toArray();
            if (!empty($roles)) {
                $query->orWhereIn('role', $roles);
            }

            // 4. إشعارات عامة
            $query->orWhere(function($q) {
                $q->whereNull('user_id')
                    ->whereNull('branch_id')
                    ->whereNull('role');
            });
        })
            // ✅✅✅ استبعاد الإشعارات اللي المستخدم نفسه عملها
            ->where('created_by', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    // ════════════════════════════════════════════════════════════
    // 🔔 NOTIFICATION HELPERS - NO CREATOR NOTIFICATIONS
    // ════════════════════════════════════════════════════════════

    /**
     * إشعار فاتورة جديدة
     */
    /**
     * إشعار فاتورة جديدة
     */
    public static function invoiceCreated($invoice)
    {
        // 1️⃣ إشعار للفرع
        self::notifyBranch($invoice->branch_id, [
            'type' => 'invoice_created',
            'title' => '🧾 فاتورة جديدة',
            'message' => "تم إنشاء فاتورة جديدة رقم {$invoice->invoice_code} بقيمة {$invoice->total}",
            'data' => [
                'invoice_id' => $invoice->id,
                'invoice_code' => $invoice->invoice_code,
                'total' => $invoice->total,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.invoice.show', $invoice->invoice_code),
        ]);

        // 2️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'invoice_created',
            'title' => '🧾 فاتورة جديدة',
            'message' => "تم إنشاء فاتورة جديدة رقم {$invoice->invoice_code} في فرع {$invoice->branch->name}",
            'data' => [
                'invoice_id' => $invoice->id,
                'invoice_code' => $invoice->invoice_code,
                'total' => $invoice->total,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.invoice.show', $invoice->invoice_code),
        ]);

        // 3️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'invoice_created',
            'title' => '🧾 فاتورة جديدة',
            'message' => "تم إنشاء فاتورة رقم {$invoice->invoice_code} في فرع {$invoice->branch->name} بقيمة {$invoice->total}",
            'data' => [
                'invoice_id' => $invoice->id,
                'invoice_code' => $invoice->invoice_code,
                'total' => $invoice->total,
                'branch_name' => $invoice->branch->name,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.invoice.show', $invoice->invoice_code),
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // 📦 STOCK TRANSFER NOTIFICATIONS - CLEAN VERSION
    // ════════════════════════════════════════════════════════════

    /**
     * إشعار نقل منتج جديد
     */
    public static function transferCreated($transfer)
    {
        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_created',
            'title' => '📤 طلب نقل جديد',
            'message' => "تم إنشاء طلب نقل رقم {$transfer->transfer_number} إلى فرع {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'from_branch' => $transfer->fromBranch->name,
                'to_branch' => $transfer->toBranch->name,
            ],
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_created',
            'title' => '📥 طلب نقل وارد',
            'message' => "تم استلام طلب نقل رقم {$transfer->transfer_number} من فرع {$transfer->fromBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'from_branch' => $transfer->fromBranch->name,
                'to_branch' => $transfer->toBranch->name,
            ],
            'color' => 'warning',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_created',
            'title' => '📦 طلب نقل جديد',
            'message' => "طلب نقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name} إلى {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    /**
     * إشعار موافقة على نقل
     */
    public static function transferApproved($transfer)
    {
        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_approved',
            'title' => '✅ موافقة على طلب النقل',
            'message' => "تمت الموافقة على طلب النقل رقم {$transfer->transfer_number} إلى {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_approved',
            'title' => '✅ موافقة على طلب النقل',
            'message' => "تمت الموافقة على طلب النقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name}. جاهز للاستلام",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'transfer_approved',
            'title' => '✅ موافقة على طلب نقل',
            'message' => "تمت الموافقة على طلب النقل رقم {$transfer->transfer_number}",
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 4️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_approved',
            'title' => '✅ موافقة على طلب نقل',
            'message' => "تمت الموافقة على طلب النقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name} إلى {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    /**
     * إشعار طلب النقل في الطريق
     */
    public static function transferInTransit($transfer)
    {
        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_in_transit',
            'title' => '🚚 النقل في الطريق',
            'message' => "طلب النقل رقم {$transfer->transfer_number} الآن في الطريق إلى {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_in_transit',
            'title' => '🚚 طلب نقل في الطريق',
            'message' => "طلب النقل رقم {$transfer->transfer_number} الآن في الطريق إليكم من {$transfer->fromBranch->name}",
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'transfer_in_transit',
            'title' => '🚚 نقل في الطريق',
            'message' => "طلب النقل رقم {$transfer->transfer_number} الآن في الطريق",
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 4️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_in_transit',
            'title' => '🚚 نقل في الطريق',
            'message' => "طلب النقل رقم {$transfer->transfer_number} الآن في الطريق من {$transfer->fromBranch->name} إلى {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'info',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    /**
     * إشعار استلام نقل
     */
    public static function transferReceived($transfer)
    {
        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_received',
            'title' => '📦 تم استلام النقل',
            'message' => "تم استلام طلب النقل رقم {$transfer->transfer_number} في {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_received',
            'title' => '📦 تم استلام النقل',
            'message' => "تم استلام طلب النقل رقم {$transfer->transfer_number} بنجاح في فرعكم",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'transfer_received',
            'title' => '📦 تم استلام نقل',
            'message' => "تم استلام طلب النقل رقم {$transfer->transfer_number}",
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 4️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_received',
            'title' => '📦 تم استلام نقل',
            'message' => "تم استلام طلب النقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name} في {$transfer->toBranch->name}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'success',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    /**
     * إشعار رفض نقل
     */
    public static function transferRejected($transfer, $reason = null)
    {
        $reasonText = $reason ? " - السبب: {$reason}" : '';

        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_rejected',
            'title' => '❌ رفض طلب النقل',
            'message' => "تم رفض طلب النقل رقم {$transfer->transfer_number}{$reasonText}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'reason' => $reason,
            ],
            'color' => 'danger',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_rejected',
            'title' => '❌ رفض طلب النقل',
            'message' => "تم رفض طلب النقل رقم {$transfer->transfer_number}{$reasonText}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ],
            'color' => 'danger',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'transfer_rejected',
            'title' => '❌ رفض طلب نقل',
            'message' => "تم رفض طلب النقل رقم {$transfer->transfer_number}",
            'color' => 'danger',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 4️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_rejected',
            'title' => '❌ رفض طلب نقل',
            'message' => "تم رفض طلب النقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name} إلى {$transfer->toBranch->name}{$reasonText}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'reason' => $reason,
            ],
            'color' => 'danger',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    /**
     * إشعار إلغاء نقل
     */
    public static function transferCanceled($transfer, $reason = null)
    {
        $reasonText = $reason ? " - السبب: {$reason}" : '';

        // 1️⃣ إشعار للفرع المرسل
        self::notifyBranch($transfer->from_branch_id, [
            'type' => 'transfer_canceled',
            'title' => '🚫 إلغاء طلب النقل',
            'message' => "تم إلغاء طلب النقل رقم {$transfer->transfer_number}{$reasonText}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'reason' => $reason,
            ],
            'color' => 'warning',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 2️⃣ إشعار للفرع المستقبل
        self::notifyBranch($transfer->to_branch_id, [
            'type' => 'transfer_canceled',
            'title' => '🚫 إلغاء طلب النقل',
            'message' => "تم إلغاء طلب النقل رقم {$transfer->transfer_number}{$reasonText}",
            'color' => 'warning',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 3️⃣ إشعار للمديرين
        self::notifyManagers([
            'type' => 'transfer_canceled',
            'title' => '🚫 إلغاء طلب نقل',
            'message' => "تم إلغاء طلب النقل رقم {$transfer->transfer_number}",
            'color' => 'warning',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);

        // 4️⃣ إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'transfer_canceled',
            'title' => '🚫 إلغاء طلب نقل',
            'message' => "تم إلغاء طلب النقل رقم {$transfer->transfer_number} من {$transfer->fromBranch->name} إلى {$transfer->toBranch->name}{$reasonText}",
            'data' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'reason' => $reason,
            ],
            'color' => 'warning',
            'action_url' => route('dashboard.stock-transfers.show', $transfer->id),
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // 📊 STOCK NOTIFICATIONS
    // ════════════════════════════════════════════════════════════

    /**
     * إشعار مخزون منخفض
     */
    public static function lowStock($product, $branch)
    {
        // إشعار للفرع
        self::notifyBranch($branch->id, [
            'type' => 'low_stock',
            'title' => '⚠️ تحذير: مخزون منخفض',
            'message' => "المنتج {$product->description} أوشك على النفاد في فرع {$branch->name}",
            'data' => [
                'product_id' => $product->id,
                'branch_id' => $branch->id,
            ],
            'color' => 'warning',
            'action_url' => route('dashboard.branches.stock.index', $branch->id),
        ]);

        // إشعار للمديرين
        self::notifyManagers([
            'type' => 'low_stock',
            'title' => '⚠️ تحذير: مخزون منخفض',
            'message' => "المنتج {$product->description} أوشك على النفاد في فرع {$branch->name}",
            'color' => 'warning',
        ]);
    }

    /**
     * إشعار مخزون نفذ
     */
    public static function outOfStock($product, $branch)
    {
        // إشعار للفرع
        self::notifyBranch($branch->id, [
            'type' => 'out_of_stock',
            'title' => '🚨 تحذير: نفذ المخزون',
            'message' => "المنتج {$product->description} نفذ من المخزون في فرع {$branch->name}",
            'color' => 'danger',
        ]);

        // إشعار للسوبر أدمن
        self::notifySuperAdmins([
            'type' => 'out_of_stock',
            'title' => '🚨 تحذير: نفذ المخزون',
            'message' => "المنتج {$product->description} نفذ من المخزون في فرع {$branch->name}",
            'color' => 'danger',
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // 👥 USER NOTIFICATIONS
    // ════════════════════════════════════════════════════════════

    /**
     * إشعار مستخدم جديد
     */
    public static function userCreated($user)
    {
        self::notifySuperAdmins([
            'type' => 'user_created',
            'title' => '👤 مستخدم جديد',
            'message' => "تم إضافة مستخدم جديد: {$user->full_name}",
            'color' => 'info',
            'action_url' => route('dashboard.get-all-users'),
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // 💰 EXPENSE NOTIFICATIONS
    // ════════════════════════════════════════════════════════════

    /**
     * إشعار مصروف جديد
     */
    public static function expenseCreated($expense)
    {
        // إشعار للفرع
        self::notifyBranch($expense->branch_id, [
            'type' => 'expense_created',
            'title' => '💸 مصروف جديد',
            'message' => "تم إضافة مصروف جديد بقيمة {$expense->amount}",
            'color' => 'warning',
        ]);

        // إشعار للمديرين
        self::notifyManagers([
            'type' => 'expense_created',
            'title' => '💸 مصروف جديد',
            'message' => "تم إضافة مصروف في فرع {$expense->branch->name} بقيمة {$expense->amount}",
            'color' => 'warning',
        ]);
    }
}
