<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\User;
use App\Branch;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function __construct()
    {
        // Permissions
        $this->middleware('permission.spatie:view-users')->only(['index', 'getAddUser', 'getUpdateUser']);
        $this->middleware('permission.spatie:create-users')->only(['postAddUser']);
        $this->middleware('permission.spatie:edit-users')->only(['postUpdateUser', 'toggleActive']);
        $this->middleware('permission.spatie:delete-users')->only(['deleteAdmin']);
       // $this->middleware('permission.spatie:assign-roles')->only(['postAddUser', 'postUpdateUser']);
    }
    /**
     * Display a listing of users
     */
   /* public function index(Request $request)
    {
        $users = User::with(['branch', 'roles'])
            ->when($request->search, function($query) use ($request) {
                return $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('dashboard.pages.users.index', compact('users'));
    }*/

    public function index(Request $request)
    {
        $authUser = auth()->user();

        $users = User::with(['branch', 'roles'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })

            ->accessibleByUser($authUser, $request->branch_id)

            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('dashboard.pages.users.index', compact('users'));
    }


    /**
     * Show the form for creating a new user
     */
    public function getAddUser()
    {
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();

        return view('dashboard.pages.users.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function postAddUser(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'salary' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'first_name.required' => 'Please enter the first name',
            'last_name.required' => 'Please enter the last name',
            'email.required' => 'Please enter the email',
            'email.unique' => 'This email has already been taken!',
            'password.required' => 'Please enter the password',
            'password.confirmed' => 'Passwords do not match',
            'image.image' => 'Please upload an image file',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif',
            'image.max' => 'Image size must not exceed 2MB',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $request_data = $request->except(['password', 'password_confirmation', 'image', 'roles']);
            $request_data['password'] = bcrypt($request->password);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/uploads/images/users'), $imageName);
                $request_data['image'] = $imageName;
            } else {
                $request_data['image'] = 'default.png';
            }

            $user = User::create($request_data);

            // Assign roles if provided
            if ($request->filled('roles')) {
                $user->syncRoles($request->roles);
            }

            // ✅ إشعار السوبر أدمن
            NotificationService::userCreated($user);

            DB::commit();

            session()->flash('success', 'User added successfully!');
            return redirect()->route('dashboard.get-all-users');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error adding user: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function getUpdateUser($id)
    {
        $user = User::with(['branch', 'roles'])->findOrFail($id);
        $branches = Branch::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();

        return view('dashboard.pages.users.edit', compact('user', 'branches', 'roles'));
    }

    /**
     * Update the specified user
     */
    /*public function postUpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'branch_id' => 'nullable|exists:branches,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'salary' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'first_name.required' => 'Please enter the first name',
            'last_name.required' => 'Please enter the last name',
            'email.required' => 'Please enter the email',
            'email.unique' => 'This email has already been taken!',
            'image.image' => 'Please upload an image file',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif',
            'image.max' => 'Image size must not exceed 2MB',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $request_data = $request->except(['image', 'roles']);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if not default
                if ($user->image != 'default.jpg' && $user->image) {
                    $oldImagePath = storage_path('app/public/uploads/images/users/' . $user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/uploads/images/users'), $imageName);
                $request_data['image'] = $imageName;
            }

            $user->update($request_data);

            // Update roles if provided
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            DB::commit();

            session()->flash('success', 'User updated successfully!');
            return redirect()->route('dashboard.get-all-users');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }*/

    public function postUpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],

            // ✅ password optional
            'password' => 'nullable|string|min:6|confirmed',

            'branch_id' => 'nullable|exists:branches,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'salary' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'first_name.required' => 'Please enter the first name',
            'last_name.required' => 'Please enter the last name',
            'email.required' => 'Please enter the email',
            'email.unique' => 'This email has already been taken!',

            // ✅ Password messages
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',

            'image.image' => 'Please upload an image file',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif',
            'image.max' => 'Image size must not exceed 2MB',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $request_data = $request->except(['image', 'roles', 'password']);

            // ✅ Handle is_active checkbox
            $request_data['is_active'] = $request->has('is_active') ? 1 : 0;

            // ✅ Handle password only if entered
            if ($request->filled('password')) {
                $request_data['password'] = Hash::make($request->password);
            }

            // Handle image upload
            if ($request->hasFile('image')) {

                if ($user->image != 'default.jpg' && $user->image) {
                    $oldImagePath = storage_path('app/public/uploads/images/users/' . $user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/uploads/images/users'), $imageName);
                $request_data['image'] = $imageName;
            }

            $user->update($request_data);

            // Update roles if provided
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            DB::commit();

            session()->flash('success', 'User updated successfully!');
            return redirect()->route('dashboard.get-all-users');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    /*public function deleteAdmin($id)
    {
        $user = User::findOrFail($id);

        if (!$user->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف آخر Super Admin');
        }

        DB::beginTransaction();
        try {
            // Delete user image if not default
            if ($user->image != 'default.jpg' && $user->image) {
                $imagePath = storage_path('app/public/uploads/images/users/' . $user->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Remove all roles before deleting user
            $user->syncRoles([]);

            $user->delete();

            DB::commit();

            session()->flash('success', 'User deleted successfully!');
            return redirect()->route('dashboard.get-all-users');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
            return redirect()->back();
        }
    }*/

    /**
     * Delete user with protection
     */
    public function deleteAdmin($id)
    {
        $user = User::findOrFail($id);

        // لا يمكن حذف Super Admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot delete Super Admin user');
        }

        // فحص إذا المستخدم يمكن حذفه
        if (!$user->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف آخر Super Admin في النظام');
        }

        // فحص إذا المستخدم مرتبط ببيانات (Invoices, Transactions, etc)
        if ($user->createdInvoices()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المستخدم - المستخدم مرتبط بفواتير في النظام');
        }

        if ($user->cashierTransactions()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المستخدم - المستخدم مرتبط بمعاملات مالية');
        }

        DB::beginTransaction();
        try {
            // Delete user image if not default
            if ($user->image != 'default.jpg' && $user->image) {
                $imagePath = storage_path('app/public/uploads/images/users/' . $user->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Remove all roles before deleting user
            $user->syncRoles([]);

            $user->delete();

            DB::commit();

            session()->flash('success', 'User deleted successfully!');
            return redirect()->route('dashboard.get-all-users');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Toggle user active status
     */
    /*public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        try {
            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "User {$status} successfully",
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status'
            ], 500);
        }
    }*/

    /**
     * Toggle user active status (AJAX)
     */
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        // لا يمكن تغيير حالة Super Admin
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change Super Admin status'
            ], 403);
        }

        try {
            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "User {$status} successfully",
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status'
            ], 500);
        }
    }


    /**
     * عرض صفحة الملف الشخصي
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('dashboard.pages.users.profile', compact('user'));
    }

    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ];

        $messages = [
            'first_name.required' => 'من فضلك أدخل الاسم الأول',
            'last_name.required'  => 'من فضلك أدخل الاسم الأخير',
            'email.required'      => 'من فضلك أدخل البريد الإلكتروني',
            'email.unique'        => 'هذا البريد الإلكتروني مسجّل بالفعل',
            'image.file'          => 'يرجى رفع ملف صورة صالح',
            'image.mimes'         => 'الصورة يجب أن تكون بصيغة: jpeg أو png أو jpg أو gif أو webp',
            'image.max'           => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $data = $request->only(['first_name', 'last_name', 'email']);

            // Handle image upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete old image if not default
                if ($user->image && $user->image != 'default.jpg' && $user->image != 'default.png') {
                    $oldImagePath = storage_path('app/public/uploads/images/users/' . $user->image);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }

                $uploadPath = storage_path('app/public/uploads/images/users');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                $image     = $request->file('image');
                $ext       = $image->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $ext;
                $image->move($uploadPath, $imageName);
                $data['image'] = $imageName;
            }

            $user->update($data);

            DB::commit();

            return redirect()->route('dashboard.profile.show')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ];

        $messages = [
            'current_password.required' => 'Please enter your current password',
            'new_password.required' => 'Please enter a new password',
            'new_password.min' => 'Password must be at least 6 characters',
            'new_password.confirmed' => 'Password confirmation does not match',
        ];

        $request->validate($rules, $messages);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect')
                ->withInput();
        }

        // Update password
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->route('dashboard.profile.show')
            ->with('success', 'Password changed successfully!');
    }
}
