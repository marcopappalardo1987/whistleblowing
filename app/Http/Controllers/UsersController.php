<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function view() {
        $users = User::with('roles')->paginate(20); // Retrieve users with their roles and pagination
        return view('manage.users', compact('users')); // Pass users to the view
    }

    public function edit($user_id) {
        $user = User::with('roles')->findOrFail($user_id); // Retrieve the user with their roles by user_id
        $roles = Role::all(); // Retrieve all roles from the database
        return view('manage.users.edit', compact('user', 'roles')); // Pass the user and roles to the view
    }

    public function update(Request $request, $user_id){
        $user = User::with('roles')->findOrFail($user_id); // Retrieve the user with their roles by user_id
        
        // Prepare data for update
        $data = $request->only('name', 'email');
        
        // Update password only if it's not empty
        if (!empty($request->input('password'))) {
            $data['password'] = bcrypt($request->input('password')); // Hash the password before saving
        }
        
        // Update roles if provided
        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles')); // Sync the roles with the user
        }
        
        $user->update($data); // Update user details
        return redirect()->route('users.edit', ['user_id' => $user->id])->with('success', __('User updated successfully.')); // Redirect back to the edit page with success message
    }

    public function add(){
        $roles = Role::all();
        return view('manage.users.add', compact('roles'));
    }

    public function store(Request $request){
        try {
            $messages = [
                'name.required' => __('The name field is required.'),
                'email.required' => __('The email field is required.'),
                'email.email' => __('Please enter a valid email address.'),
                'email.unique' => __('This email is already registered.'),
                'password.required' => __('The password field is required.'),
                'password.min' => __('The password must be at least 8 characters.'),
                'password.confirmed' => __('The password confirmation does not match.'),
                'roles.required' => __('Please select at least one role.'),
            ];

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name',
            ], $messages);

            // Create a new user with the validated data
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            // Assign roles to the newly created user
            $user->syncRoles($data['roles']);            

            return redirect()->route('users')->with('success', __('User created successfully.'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            $messages['error'] = implode(', ', $e->validator->errors()->all()); // Get all validation errors as a single string
            return back()
                ->withInput()
                ->with($messages); // Pass the messages array to the session
        }
    }

    /**
     * Delete a user by their ID.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($user_id){
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect()->route('users')->with('success', __('User deleted successfully.'));
    }

}
