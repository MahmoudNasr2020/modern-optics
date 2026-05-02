<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-roles')->only(['index', 'show']);
        $this->middleware('permission.spatie:create-roles')->only(['create', 'store']);
        $this->middleware('permission.spatie:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission.spatie:delete-roles')->only('destroy');
        $this->middleware('permission.spatie:assign-permissions')->only(['editPermissions', 'updatePermissions']);
    }

    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::withCount('users')
            ->orderBy('is_system', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return  view('dashboard.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        return  view('dashboard.pages.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
            'display_name.required' => 'Display name is required',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'guard_name' => 'web',
                'is_active' => true,
                'is_system' => false,
            ]);

            if ($request->filled('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return redirect()
                ->route('dashboard.roles.index')
                ->with('success', 'Role created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error creating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        return  view('dashboard.pages.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        if ($role->is_system) {
            return redirect()
                ->back()
                ->with('error', 'Cannot edit system role');
        }

        $permissions = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        return  view('dashboard.pages.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return redirect()
                ->back()
                ->with('error', 'Cannot edit system role');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
            'display_name.required' => 'Display name is required',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);

            // Update permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            return redirect()
                ->route('dashboard.roles.index')
                ->with('success', 'Role updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error updating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete system role');
        }

        if ($role->users()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete role with assigned users. Please reassign users first.');
        }

        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();

            return redirect()
                ->route('dashboard.roles.index')
                ->with('success', 'Role deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Show permissions assignment form
     */
    public function editPermissions(Role $role)
    {
        $permissions = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return  view('dashboard.pages.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->syncPermissions($request->permissions ?? []);
            DB::commit();

            return redirect()
                ->route('dashboard.roles.index')
                ->with('success', 'Permissions updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }
}
