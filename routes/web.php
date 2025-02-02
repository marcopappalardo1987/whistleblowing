<?php
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AffiliateController;
use App\Http\Middleware\CheckUserPlanAndRole;
use App\Http\Controllers\CompanyDataController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Frontend\PlansController;
use App\Http\Controllers\AffiliateEarningController;
use App\Http\Controllers\PermissionsAssignerController;
use App\Http\Controllers\SubscriptionProfileController;
use App\Http\Controllers\AffiliateCommissionsController;
use Illuminate\Support\Facades\Route; // Import the Route facade for defining routes
use App\Http\Controllers\RolesController; // Import the RolesController for managing roles
use App\Http\Controllers\ProfileController; // Import the ProfileController for handling user profile actions

// Define a route for the home page that returns the welcome view
Route::get('/', function () {
    return view('welcome'); // Return the welcome view
});

Route::get('/piani', [PlansController::class, 'index'])
    ->name('plans');

Route::middleware(['auth'])->group(function () {
    Route::get('/pagamento', [SubscriptionController::class, 'showCheckoutForm'])->name('checkout');
    Route::post('/pagamento/processo', [SubscriptionController::class, 'processCheckout'])->name('checkout.process');
});

// Registrazione affiliato
Route::get('/registrazione/affiliato', function () {
    return view('auth.register-affiliate');
})->name('register.affiliate');

Route::post('/registrazione/affiliato', [AffiliateController::class, 'registerAffiliate'])
    ->name('register.affiliate.store');

//Commissioni
Route::get('/pannello/affiliati/commissioni', [AffiliateCommissionsController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('affiliate.settings.commissions');

Route::post('/pannello/affiliati/commissioni', [AffiliateCommissionsController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('affiliate.settings.commissions.store');

// Define a route for editing an affiliate commission, requiring authentication, verification, and permission to edit owner data
Route::get('/pannello/affiliati/commissioni/{id}/modifica', [AffiliateCommissionsController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.edit');

// Define a route for updating an affiliate commission, requiring authentication, verification, and permission to edit owner data
Route::post('/pannello/affiliati/commissioni/{id}', [AffiliateCommissionsController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.update');

// Define a route for deleting an affiliate commission, requiring authentication, verification, and permission to remove owner data
Route::delete('/pannello/affiliati/commissioni/{id}', [AffiliateCommissionsController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.destroy'); // Name the route

Route::get('/pannello/area-privata/referal-link', function () {
    return view('affiliate.private-area.links'); // Restituisce la vista per i link di riferimento
})->middleware(['auth', 'verified', 'permission:view affiliate data'])->name('affiliate.private-area.links'); // Applica middleware di autenticazione, verifica e permesso per affiliato

Route::get('/affiliate/private-area/affiliates-list', [AffiliateController::class, 'ownAffiliates'])
    ->name('affiliate.private-area.affiliates-list');

Route::get('/affiliate/private-area/earnings', [AffiliateEarningController::class, 'show'])
    ->name('affiliate.private-area.earnings');








Route::middleware(CheckUserPlanAndRole::class)->group(function () {
    // Define a route for the dashboard that requires authentication and verification
    Route::get('/pannello', function () {
        return view('dashboard'); // Return the dashboard view
    })->middleware(['auth', 'verified'])->name('dashboard'); // Apply auth and verified middleware
}); 


// Define a route for the manage page that requires authentication, verification, and permission to view owner data
Route::get('/pannello/gestione', function () {
    return view('manage'); // Return the manage view
})->middleware(['auth', 'verified', 'permission:view ownerdata'])->name('manage'); // Apply necessary middleware

/*********************************
 ********************************* 
 * Roles Routes
 *********************************
 *********************************/

// Define a route for showing the add role form, requiring authentication, verification, and permission to publish owner data
Route::get('/pannello/gestione/ruoli/aggiungi', [RolesController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.add'); // Name the route

// Define a route for storing a new role, requiring authentication, verification, and permission to publish owner data
Route::post('/pannello/gestione/ruoli/salva', [RolesController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.store'); // Name the route

// Define a route for managing roles, requiring authentication, verification, and permission to view owner data
Route::get('/pannello/gestione/ruoli', [RolesController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('roles'); // Name the route

// Define a route for editing roles, requiring authentication, verification, and permission to edit owner data
Route::get('/pannello/gestione/ruoli/modifica', [RolesController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/pannello/gestione/ruoli', [RolesController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.update'); // Name the route

// Define a route for deleting a role, requiring authentication, verification, and permission to remove owner data
Route::delete('/pannello/gestione/gestione-ruoli', [RolesController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('roles.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions Routes
 *********************************
 *********************************/

// Define a route for showing the add permission form, requiring authentication, verification, and permission to publish owner data
Route::get('/pannello/gestione/permessi/aggiungi', [PermissionsController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata'])
    ->name('permissions.add'); // Apply necessary middleware

// Define a route for storing a new permission, requiring authentication, verification, and permission to publish owner data
Route::post('/pannello/gestione/permessi/salva', [PermissionsController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('permissions.store'); // Name the route

// Define a route for managing permission, requiring authentication, verification, and permission to view owner data
Route::match(['get', 'post'], '/pannello/gestione/permessi', [PermissionsController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions'); // Name the route

// Define a route for editing permission, requiring authentication, verification, and permission to edit owner data
Route::get('/pannello/gestione/permessi/modifica', [PermissionsController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/pannello/gestione/permessi', [PermissionsController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.update'); // Name the route

// Define a route for deleting a permission, requiring authentication, verification, and permission to remove owner data
Route::delete('/pannello/gestione/permessi', [PermissionsController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('permissions.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions and Roles Sync Routes
 *********************************
 *********************************/

// Define a route for viewing and posting to the edit permissions for roles page, requiring authentication, verification, and permission to edit owner data
Route::get('/pannello/gestione/assegnazione-permessi/modifica', [PermissionsAssignerController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner'); // Name the route

// Define a route for storing updates to permissions for roles, requiring authentication, verification, and permission to edit owner data
Route::post('/pannello/gestione/assegnazione-permessi/modifica', [PermissionsAssignerController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata|remove ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner.store'); // Name the route

/*********************************
 ********************************* 
 * Users Routes
 *********************************
 *********************************/

Route::get('/pannello/gestione/utenti', [UsersController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view users'])
    ->name('users');

Route::match(['get', 'put'], '/pannello/gestione/utenti/modifica/{user_id}', [UsersController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.edit');

Route::post('/pannello/gestione/utenti/aggiorna/{user_id}', [UsersController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.update');

Route::match(['get', 'post'], '/pannello/gestione/utenti/aggiungi', [UsersController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.add');

Route::post('/pannello/gestione/utenti/salva', [UsersController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.store');

Route::match(['get', 'delete'], '/pannello/gestione/utenti/elimina/{user_id}', [UsersController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove users'])
    ->name('users.delete');

/*********************************
 ********************************* 
 * Products Routes
 *********************************
 *********************************/

Route::middleware(['auth', 'verified'])->group(function () {
    // Lista prodotti
    Route::get('/pannello/prodotti', [ProductsController::class, 'index'])
        ->middleware('permission:view products')
        ->name('products');

    // Aggiungi prodotto
    Route::get('/pannello/prodotti/aggiungi', [ProductsController::class, 'create'])
        ->middleware('permission:publish products')
        ->name('product.add');

    // Modifica prodotto
    Route::get('/pannello/prodotti/modifica/{product}', [ProductsController::class, 'edit'])
        ->middleware('permission:edit products')
        ->name('product.edit');

    // Salva nuovo prodotto
    Route::post('/pannello/prodotti/salva', [ProductsController::class, 'store'])
        ->middleware('permission:publish products')
        ->name('product.store');

    // Aggiorna prodotto esistente
    Route::put('/pannello/prodotti/aggiorna/{product}', [ProductsController::class, 'update'])
        ->middleware('permission:edit products')
        ->name('product.update');

    // Elimina prodotto
    Route::get('/pannello/prodotti/elimina/{product}', [ProductsController::class, 'destroy'])
        ->middleware('permission:remove products')
        ->name('product.delete');

    // Visualizza singolo prodotto
    Route::get('/pannello/prodotti/{product}', [ProductsController::class, 'show'])
        ->middleware('permission:view products')
        ->name('product.show');
});

/*********************************
 ********************************* 
 * Logs Routes
 *********************************
 *********************************/

Route::get('/pannello/logs', [LogsController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs');

Route::get('/pannello/logs/laravel', [LogsController::class, 'laravel'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs.laravel');

Route::post('/pannello/logs/pulisci', [LogsController::class, 'clearLaravelLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.clear');

Route::get('/pannello/logs/worker', [LogsController::class, 'worker'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs.worker');

Route::post('/pannello/logs/worker/pulisci', [LogsController::class, 'clearWorkerLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.worker.clear');


/*********************************
 ********************************* 
 * Profile Routes Managed by Breeze
 *********************************
 *********************************/

// Group routes that require authentication
Route::middleware('auth')->group(function () {
    // Define a route for editing the user profile
    Route::get('/pannello/profilo', [ProfileController::class, 'edit'])->name('profile.edit'); // Name the route

    // Define a route for updating the user profile
    Route::patch('/pannello/profilo', [ProfileController::class, 'update'])->name('profile.update'); // Name the route

    // Define a route for deleting the user profile
    Route::delete('/pannello/profilo', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Name the route

    Route::get('/pannello/profilo/abbonamento', [SubscriptionProfileController::class, 'subscription'])->name('profile.subscription'); // Name the route

    Route::get('/pannello/profilo/azienda/{user}', [CompanyDataController::class, 'edit'])
        ->name('company.edit');

    Route::patch('/pannello/profilo/azienda/{user}', [CompanyDataController::class, 'createOrUpdate'])
        ->name('company.update');

});

/*********************************
 ********************************* 
 * Stripe Routes
 *********************************
 *********************************/

Route::get('/pannello/abbonamenti/tutti', [SubscriptionController::class, 'showAllSubscriptionsOfAllUsers'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.all');

Route::get('/pannello/abbonamenti/modifica/{id}', [SubscriptionController::class, 'showEditForm'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.edit');

Route::patch('/pannello/abbonamenti/modifica/{id}', [SubscriptionController::class, 'updateSubscription'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('subscriptions.update');

Route::get('/pannello/abbonamenti/visualizza/{id}', [SubscriptionController::class, 'showSubscriptionByStripeId'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.view');

// Require the authentication routes defined in auth.php
require __DIR__.'/auth.php';
