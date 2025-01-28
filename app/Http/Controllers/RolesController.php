<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    
    /**
     * Display the form to add a new role.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        return view('manage.roles.add');
    }

    /**
     * Handle the request to create a new role.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        // Retrieve the posted data from the request
        $postData = $request->post();
        $messages = [];

        // Check if the 'name' field is present in the posted data
        if ($postData['name']) {
            $roleName = $postData['name'];
            // Check if a role with the same name already exists
            $roleExists = Role::where('name', $roleName)->exists();
            if ($roleExists) {
                // If the role exists, set an error message
                $messages['error'] = __("The User Role you're trying to create already exists.");
            } else {
                // Create a new role if it does not exist
                $role = Role::create(['name' => $roleName]);
                $messages['success'] = __("The User Role has been successfully created.");
            }
        }
        
        return redirect()->route('roles')->with($messages);

    }

    /**
     * Display all existing roles.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Retrieve all roles from the database
        $roles = Role::all();

        // Return the view with the list of roles
        return view('manage.roles', [
            'roles' => $roles
        ]);
    }

    /**
     * Display the form to edit roles.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request) {
    
        $data = $request->all(); // Retrieve all parameters from the POST request
        $roleId = $data['role_id'] ?? null; // Get the role ID from the request data
        if ($roleId) {
            $role = Role::find($roleId); // Attempt to find the role by its ID
            if ($role) {
                $roleName = $role->name; // Retrieve the name of the role
                // You can use $roleName as needed, for example, passing it to the view
            } else {
                // Handle the case where the role is not found
                return redirect()->route('roles')->with('error', 'Role not found.');
            }
        }

        // Return the view with the list of roles for editing
        return view('manage.roles.edit', [
            'roleName' => $roleName
        ]);
    }

    /**
     * Handle the request to update a role's name.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){

        // Retrieve the role ID from the request input
        $roleId = $request->input('role_id');
        
        // Attempt to find the role by its ID
        $role = Role::find($roleId);

        // Check if the role was found
        if (!$role) {
            // If not found, redirect to the manage roles page with an error message
            return redirect()->route('manage-roles')->with('error', 'Role not found.');
        } else {
            // If found, update the role's name with the new value from the request
            $role->name = $request->input('name');
            // Save the updated role to the database
            $role->save();
            // Redirect back to the manage roles page with a success message
            return redirect()->route('roles')->with('success', 'Role updated successfully.');
        }

    }

    /**
     * Handle the request to delete a role.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function delete(Request $request) {
        // Initialize an empty array to hold messages
        $messages = [];

        // Check if the role_id is present in the request
        if(isset($request->role_id)){
            // Attempt to find the role by its ID
            $role = Role::find($request->role_id);
            // If the role exists, proceed to delete it
            if ($role) {
                $role->delete(); // Delete the role from the database
                $messages['success'] = __("The role has been successfully removed.");
            } else {
                // If the role does not exist, set an error message
                $messages['error'] = __("The role you are trying to delete does not exist.");
            }
        }

        // Redirect to the roles management page with messages
        return redirect()->route('roles')->with($messages);
    }

}
