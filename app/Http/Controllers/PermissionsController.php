<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{

    /**
     * Display the form to add a new permission.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        return view('manage.permissions.add');
    }

    /**
     * Handle the request to create a new permission.
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
            $permissionName = $postData['name'];
            // Check if a permission with the same name already exists
            $permissionExists = Permission::where('name', $permissionName)->exists();
            if ($permissionExists) {
                // If the permission exists, set an error message
                $messages['error'] = __("The Permission you're trying to create already exists.");
            } else {
                // Create a new permission if it does not exist
                $permission = Permission::create(['name' => $permissionName]);
                $messages['success'] = __("The Permission has been successfully created.");
            }
        }

        // Redirect to the permissions management page with messages
        return redirect()->route('permissions')->with($messages);
        
    }
    
    /**
     * Display all existing permissions.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Retrieve all permissions from the database
        $permissions = Permission::all();

        // Return the view with the list of permissions
        return view('manage.permissions', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Display the form to edit permissions.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request) {

        $data = $request->all(); // Retrieve all parameters from the POST request
        $permissionId = $data['permission_id'] ?? null; // Get the permission ID from the request data
        if ($permissionId) {
            $permission = Permission::find($permissionId); // Attempt to find the permission by its ID
            if ($permission) {
                $permissionName = $permission->name; // Retrieve the name of the permission
                // You can use $permissionName as needed, for example, passing it to the view
            } else {
                // Handle the case where the permission is not found
                return redirect()->route('permissions')->with('error', 'Permission not found.');
            }
        }

        // Return the view with the list of permissions for editing
        return view('manage.permissions.edit', [
            'permissionName' => $permissionName
        ]);
    }

    /**
     * Handle the request to update a permission's name.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){

        // Retrieve the permission ID from the request input
        $permissionId = $request->input('permission_id');
        
        // Attempt to find the permission by its ID
        $permission = Permission::find($permissionId);

        // Check if the permission was found
        if (!$permission) {
            // If not found, redirect to the manage permissions page with an error message
            return redirect()->route('permissions')->with('error', 'Permission not found.');
        } else {
            // If found, update the permission's name with the new value from the request
            $permission->name = $request->input('name');
            // Save the updated permission to the database
            $permission->save();
            // Redirect back to the manage permissions page with a success message
            return redirect()->route('permissions')->with('success', 'Permission updated successfully.');
        }

    }

    /**
     * Handle the request to delete a permission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function delete(Request $request) {
        // Initialize an empty array to hold messages
        $messages = [];

        // Check if the permission_id is present in the request
        if(isset($request->permission_id)){
            // Attempt to find the permission by its ID
            $permission = Permission::find($request->permission_id);
            // If the permission exists, proceed to delete it
            if ($permission) {
                $permission->delete(); // Delete the permission from the database
                $messages['success'] = __("The permission has been successfully removed.");
            } else {
                // If the permission does not exist, set an error message
                $messages['error'] = __("The permission you are trying to delete does not exist.");
            }
        }

        // Redirect to the permissions management page with messages
        return redirect()->route('permissions')->with($messages);
    }

}
