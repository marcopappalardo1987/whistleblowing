<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class PermissionsAssignerController
 *
 * This controller handles the management of permissions for roles.
 * It provides methods to view the permissions assigned to roles
 * and to update those permissions based on user input.
 */
class PermissionsAssignerController extends Controller
{
    /**
     * Display the view for editing permissions for roles.
     *
     * This method retrieves all roles and permissions from the database
     * and passes them to the view for editing.
     *
     * @return \Illuminate\View\View
     */
    public function view() {
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();

        return view('manage.permission-assigner.edit', compact('roles', 'permissions'));
    }

    /**
     * Store the updated permissions for roles.
     *
     * This method validates the incoming request data and updates
     * the permissions for each role based on the provided input.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    // Valida solo che "permissions" sia un array
    $request->validate([
        'permissions' => 'array', // L'array può essere vuoto o assente
    ]);

    // Recupera tutti i ruoli dal database
    $allRoles = \Spatie\Permission\Models\Role::all();

    // Itera su tutti i ruoli per assicurarsi che i permessi siano sincronizzati, anche se vuoti
    foreach ($allRoles as $role) {
        // Se il ruolo è presente nella request, prendi i permessi forniti; altrimenti usa un array vuoto
        $permissionIds = $request->permissions[$role->id] ?? [];
        
        // Recupera solo permessi validi
        $validPermissionIds = \Spatie\Permission\Models\Permission::pluck('id')->toArray();
        $permissionIds = array_intersect($permissionIds, $validPermissionIds);

        // Recupera i nomi dei permessi validi
        $permissionNames = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        // Sincronizza i permessi del ruolo (rimuove tutti se l'array è vuoto)
        $role->syncPermissions($permissionNames);
    }

    // Ritorna con messaggio di successo
    return redirect()->route('permissions-assigner')->with('success', 'Permissions updated successfully.');
}

}
