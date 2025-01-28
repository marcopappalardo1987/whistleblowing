<?php
use App\Http\Controllers\Frontend\PlansController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\PermissionsAssignerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController; // Import the ProfileController for handling user profile actions
use App\Http\Controllers\RolesController; // Import the RolesController for managing roles
use App\Http\Controllers\ScrapeListContentController;
use App\Http\Controllers\ScraperListController;
use App\Http\Controllers\UsersController;
use App\Models\ScrapeList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; // Import the Route facade for defining routes
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionProfileController;

// Define a route for the home page that returns the welcome view
Route::get('/', function () {
    return view('welcome'); // Return the welcome view
});

Route::get('/plans', [PlansController::class, 'index'])
    ->name('plans');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [SubscriptionController::class, 'showCheckoutForm'])->name('checkout');
    Route::post('/checkout/process', [SubscriptionController::class, 'processCheckout'])->name('checkout.process');
});

// Define a route for the dashboard that requires authentication and verification
Route::get('/dashboard', function () {
    return view('dashboard'); // Return the dashboard view
})->middleware(['auth', 'verified'])->name('dashboard'); // Apply auth and verified middleware

// Define a route for the manage page that requires authentication, verification, and permission to view owner data
Route::get('/manage', function () {
    return view('manage'); // Return the manage view
})->middleware(['auth', 'verified', 'permission:view ownerdata'])->name('manage'); // Apply necessary middleware

/*********************************
 ********************************* 
 * Roles Routes
 *********************************
 *********************************/

// Define a route for showing the add role form, requiring authentication, verification, and permission to publish owner data
Route::get('/manage/roles/add', [RolesController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.add'); // Name the route

// Define a route for storing a new role, requiring authentication, verification, and permission to publish owner data
Route::post('/manage/roles/store', [RolesController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.store'); // Name the route

// Define a route for managing roles, requiring authentication, verification, and permission to view owner data
Route::get('/manage/roles', [RolesController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('roles'); // Name the route

// Define a route for editing roles, requiring authentication, verification, and permission to edit owner data
Route::get('/manage/roles/edit', [RolesController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/manage/roles', [RolesController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.update'); // Name the route

// Define a route for deleting a role, requiring authentication, verification, and permission to remove owner data
Route::delete('/manage/manage-roles', [RolesController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('roles.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions Routes
 *********************************
 *********************************/

// Define a route for showing the add permission form, requiring authentication, verification, and permission to publish owner data
Route::get('/manage/permissions/add', [PermissionsController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata'])
    ->name('permissions.add'); // Apply necessary middleware

// Define a route for storing a new permission, requiring authentication, verification, and permission to publish owner data
Route::post('/manage/permissions/store', [PermissionsController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('permissions.store'); // Name the route

// Define a route for managing permission, requiring authentication, verification, and permission to view owner data
Route::match(['get', 'post'], '/manage/permissions', [PermissionsController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions'); // Name the route

// Define a route for editing permission, requiring authentication, verification, and permission to edit owner data
Route::get('/manage/permission/edit', [PermissionsController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/manage/permissions', [PermissionsController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.update'); // Name the route

// Define a route for deleting a permission, requiring authentication, verification, and permission to remove owner data
Route::delete('/manage/permissions', [PermissionsController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('permissions.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions and Roles Sync Routes
 *********************************
 *********************************/

// Define a route for viewing and posting to the edit permissions for roles page, requiring authentication, verification, and permission to edit owner data
Route::get('/manage/permissions-assigner/edit', [PermissionsAssignerController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner'); // Name the route

// Define a route for storing updates to permissions for roles, requiring authentication, verification, and permission to edit owner data
Route::post('manage/permissions-assigner/edit', [PermissionsAssignerController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata|remove ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner.store'); // Name the route

/*********************************
 ********************************* 
 * Users Routes
 *********************************
 *********************************/

 Route::get('/manage/users', [UsersController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view users'])
    ->name('users');

Route::match(['get', 'put'], '/manage/users/edit/{user_id}', [UsersController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.edit');

Route::post('/manage/users/update/{user_id}', [UsersController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.update');

Route::match(['get', 'post'], '/manage/users/add', [UsersController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.add');

Route::post('/manage/users/store', [UsersController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.store');

Route::match(['get', 'delete'], '/manage/users/delete/{user_id}', [UsersController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove users'])
    ->name('users.delete');

/*********************************
 ********************************* 
 * Products Routes
 *********************************
 *********************************/

Route::middleware(['auth', 'verified'])->group(function () {
    // Lista prodotti
    Route::get('/products', [ProductsController::class, 'index'])
        ->middleware('permission:view products')
        ->name('products');

    // Aggiungi prodotto
    Route::get('/products/add', [ProductsController::class, 'create'])
        ->middleware('permission:publish products')
        ->name('product.add');

    // Modifica prodotto
    Route::get('/products/edit/{product}', [ProductsController::class, 'edit'])
        ->middleware('permission:edit products')
        ->name('product.edit');

    // Salva nuovo prodotto
    Route::post('/products/store', [ProductsController::class, 'store'])
        ->middleware('permission:publish products')
        ->name('product.store');

    // Aggiorna prodotto esistente
    Route::put('/products/update/{product}', [ProductsController::class, 'update'])
        ->middleware('permission:edit products')
        ->name('product.update');

    // Elimina prodotto
    Route::get('/products/delete/{product}', [ProductsController::class, 'destroy'])
        ->middleware('permission:remove products')
        ->name('product.delete');

    // Visualizza singolo prodotto
    Route::get('/products/{product}', [ProductsController::class, 'show'])
        ->middleware('permission:view products')
        ->name('product.show');
});

/*********************************
 ********************************* 
 * Logs Routes
 *********************************
 *********************************/

Route::get('/logs', [LogsController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs');

Route::get('/logs/laravel', [LogsController::class, 'laravel'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs.laravel');

Route::post('/logs/clear', [LogsController::class, 'clearLaravelLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.clear');

Route::get('/logs/worker', [LogsController::class, 'worker'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs.worker');

Route::post('/logs/worker/clear', [LogsController::class, 'clearWorkerLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.worker.clear');

/*********************************
 ********************************* 
 * API Settings Routes
 *********************************
 *********************************/

Route::get('/api/settings/dataforseo', [ApiDataForSEOControllerManager::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('api.settings.dataforseo');

Route::post('/api/settings/dataforseo', [ApiDataForSEOControllerManager::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('api.settings.dataforseo.store');

/*********************************
 ********************************* 
 * Profile Routes Managed by Breeze
 *********************************
 *********************************/

// Group routes that require authentication
Route::middleware('auth')->group(function () {
    // Define a route for editing the user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Name the route

    // Define a route for updating the user profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Name the route

    // Define a route for deleting the user profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Name the route

    Route::get('/profile/subscription', [SubscriptionProfileController::class, 'subscription'])->name('profile.subscription'); // Name the route
});

/*********************************
 ********************************* 
 * Stripe Routes
 *********************************
 *********************************/

Route::get('/subscriptions/all', [SubscriptionController::class, 'showAllSubscriptionsOfAllUsers'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.all');

Route::get('/subscriptions/edit/{id}', [SubscriptionController::class, 'showEditForm'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.edit');

Route::patch('/subscriptions/edit/{id}', [SubscriptionController::class, 'updateSubscription'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('subscriptions.update');

Route::get('/subscriptions/view/{id}', [SubscriptionController::class, 'showSubscriptionByStripeId'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('subscriptions.view');

// Require the authentication routes defined in auth.php
require __DIR__.'/auth.php';
