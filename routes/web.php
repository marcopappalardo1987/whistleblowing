<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AffiliateController;
use App\Http\Middleware\CheckUserPlanAndRole;
use App\Http\Controllers\CompanyDataController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\InvestigatorController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\Frontend\PlansController;
use App\Http\Controllers\AffiliateEarningController;
use App\Http\Controllers\CompanySettingFormsController;
use App\Http\Controllers\PermissionsAssignerController;
use App\Http\Controllers\SubscriptionProfileController;
use App\Http\Controllers\AffiliateCommissionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

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

// Registrazione investigatore
Route::get('/registrazione/investigatore', [InvestigatorController::class, 'register'])
    ->name('register.investigator');

Route::post('/registrazione/investigatore', [InvestigatorController::class, 'registerStore'])
    ->name('register.investigator.store');

//Commissioni
Route::get('/affiliati/commissioni', [AffiliateCommissionsController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('affiliate.settings.commissions');

Route::post('/affiliati/commissioni', [AffiliateCommissionsController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('affiliate.settings.commissions.store');

// Define a route for editing an affiliate commission, requiring authentication, verification, and permission to edit owner data
Route::get('/affiliati/commissioni/{id}/modifica', [AffiliateCommissionsController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.edit');

// Define a route for updating an affiliate commission, requiring authentication, verification, and permission to edit owner data
Route::post('/affiliati/commissioni/{id}', [AffiliateCommissionsController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.update');

// Define a route for deleting an affiliate commission, requiring authentication, verification, and permission to remove owner data
Route::delete('/affiliati/commissioni/{id}', [AffiliateCommissionsController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('affiliate.settings.commissions.destroy'); // Name the route

Route::get('/area-privata/referal-link', function () {
    return view('affiliate.private-area.links'); // Restituisce la vista per i link di riferimento
})->middleware(['auth', 'verified', 'permission:view affiliate data'])->name('affiliate.private-area.links'); // Applica middleware di autenticazione, verifica e permesso per affiliato

Route::get('/affiliate/private-area/affiliates-list', [AffiliateController::class, 'ownAffiliates'])
    ->middleware(['auth', 'verified', 'permission:view affiliate data'])
    ->name('affiliate.private-area.affiliates-list');

Route::get('/affiliate/private-area/earnings', [AffiliateEarningController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view affiliate data'])
    ->name('affiliate.private-area.earnings');

Route::get('/{slug}/segnalazioni/branch/{branch_id}', [CompanySettingController::class, 'companyPageWhistleblowing'])
    ->name('page-whistleblowing');

Route::get('/{slug}/form-segnalazioni/branch/{branch_id}', [CompanySettingController::class, 'companyPageWhistleblowingForm'])
    ->name('page-whistleblowing-form-segnalazioni');

Route::post('/{slug}/form-segnalazioni/branch/{branch_id}', [ReportController::class, 'reportStore'])
    ->name('report-store');

Route::get('/{slug}/cerca-segnalazioni/branch/{branch_id}', [CompanySettingController::class, 'companyPageWhistleblowingCercaSegnalazioni'])
    ->name('page-whistleblowing-cerca-segnalazioni');

Route::post('/{slug}/segnalazione', [ReportController::class, 'viewReport'])
    ->name('report.view');

Route::get('/{slug}/richiedi-appuntamento/branch/{branch_id}', [CompanySettingController::class, 'companyPageWhistleblowingRichiediAppuntamento'])
    ->name('page-whistleblowing-richiedi-appuntamento');

Route::get('/{slug}/report/inviato-con-successo', [ReportController::class, 'successSubmitted'])
    ->name('report.success-submitted');

Route::post('/whistleblower/segnalazione/{id}/rispondi', [ReportController::class, 'replyReport'])
    ->middleware(['auth', 'verified', 'permission:edit report'])
    ->name('whistleblower.report.reply');

Route::middleware(CheckUserPlanAndRole::class)->group(function () {

    // Define a route for the dashboard that requires authentication and verification
    Route::get('/', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); // Apply auth and verified middleware

    /**
     * Azienda
     */
    Route::get('/impostazioni/azienda', [CompanySettingController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:company settings'])
    ->name('company.settings');

    Route::post('/impostazioni/azienda', [CompanySettingController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:company settings'])
    ->name('company.settings.store');

    Route::get('/impostazioni/azienda/form', [CompanySettingFormsController::class, 'showRelatedForms'])
    ->middleware(['auth', 'verified', 'permission:company settings'])
    ->name('company.users-forms-relations');

    Route::post('/impostazioni/azienda/form', [CompanySettingFormsController::class, 'storeRelatedForms'])
    ->middleware(['auth', 'verified', 'permission:company settings'])
    ->name('company.users-forms-relations.store');

    Route::get('/branch', [BranchController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish branch'])
    ->name('branch.add');

    Route::post('/branch', [BranchController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish branch'])
    ->name('branch.form.store');

    Route::get('/branch/modifica/{id}', [BranchController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit branch'])
    ->name('branch.edit');

    Route::put('/branch/modifica/{id}', [BranchController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit branch'])
    ->name('branch.update');

    Route::get('/branch/elenco', [BranchController::class, 'list'])
    ->middleware(['auth', 'verified', 'permission:view branch'])
    ->name('branch.list');

    Route::get('/branch/elimina/{id}', [BranchController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'permission:remove branch'])
    ->name('branch.delete');

    // Define a route for the form builder, requiring authentication, verification, and permission to view owner data or company settings
    Route::get('/form-builder', [FormBuilderController::class, 'list'])
        ->middleware(['auth', 'verified', 'permission:view form builder'])
        ->name('form.builder.list'); // Applica middleware di autenticazione, verifica e permesso
    
    Route::get('/form-builder/crea', [FormBuilderController::class, 'new'])
        ->middleware(['auth', 'verified', 'permission:publish form builder'])
        ->name('form.builder.new'); // Applica middleware di autenticazione, verifica e permesso
    
    Route::get('/form-builder/modifica/{id}', [FormBuilderController::class, 'edit'])
        ->middleware(['auth', 'verified', 'permission:edit form builder'])
        ->name('form.builder.edit'); // Applica middleware di autenticazione, verifica e permesso
    
    Route::post('/form-builder/salva', [FormBuilderController::class, 'store'])
        ->middleware(['auth', 'verified', 'permission:edit form builder']) // Applica middleware di autenticazione, verifica e permesso
        ->name('form.builder.store'); // Nome della rotta
    
    Route::put('/form-builder/modifica/{id}', [FormBuilderController::class, 'update'])
        ->middleware(['auth', 'verified', 'permission:edit form builder']) // Applica middleware di autenticazione, verifica e permesso
        ->name('form.builder.update'); // Nome della rotta
    
    Route::delete('/form-builder/elimina/{id}', [FormBuilderController::class, 'destroy'])
        ->middleware(['auth', 'verified', 'permission:remove form builder']) // Applica middleware di autenticazione, verifica e permesso
        ->name('form.builder.destroy'); // Nome della rotta

    Route::get('/invita-investigatore', [InvestigatorController::class, 'invite'])
        ->middleware(['auth', 'verified', 'permission:publish investigator'])
        ->name('investigator.invite');

    Route::post('/invita-investigatore', [InvestigatorController::class, 'store'])
        ->middleware(['auth', 'verified', 'permission:publish investigator'])
        ->name('investigator.invite.store');

    Route::get('/investigatori', [InvestigatorController::class, 'list'])
    ->name('investigator.list');

    Route::get('/investigatori/modifica/{id}', [InvestigatorController::class, 'edit'])
        ->middleware(['auth', 'verified', 'permission:edit investigator'])
        ->name('investigator.edit');
    
    Route::put('/investigatori/modifica/{id}', [InvestigatorController::class, 'update'])
        ->middleware(['auth', 'verified', 'permission:edit investigator'])
        ->name('investigator.update');
    
    Route::delete('/investigatori/elimina/{id}', [InvestigatorController::class, 'destroy'])
        ->middleware(['auth', 'verified', 'permission:remove investigator'])
        ->name('investigator.destroy');

    Route::get('/investigatore', [InvestigatorController::class, 'dashboard'])
        ->middleware(['auth', 'verified', 'permission:view report'])
        ->name('investigator.dashboard');

    Route::middleware(['auth', 'verified', 'role:investigatore'])->group(function () {
        Route::get('/investigatore/segnalazioni/{status}', [InvestigatorController::class, 'reportsList'])
            ->name('investigator.reports-list');
    });

    Route::get('/investigatore/segnalazione/{id}', [InvestigatorController::class, 'viewReport'])
        ->middleware(['auth', 'verified', 'role:investigatore'])
        ->name('investigator.report.view');

    Route::post('/investigatore/segnalazione/{id}/rispondi', [InvestigatorController::class, 'replyReport'])
        ->middleware(['auth', 'verified', 'permission:edit report'])
        ->name('investigator.report.reply');

    Route::get('/investigatore/segnalazione/{id}/cambia-stato', [InvestigatorController::class, 'changeStatus'])
        ->middleware(['auth', 'verified', 'permission:edit report'])
        ->name('investigator.report.change-status');

    Route::post('/investigatore/segnalazioni/{id}/cambia-stato', [InvestigatorController::class, 'statusUpdate'])
        ->middleware(['auth', 'verified', 'permission:edit report'])
        ->name('investigator.report.status-update');
}); 

// Define a route for the manage page that requires authentication, verification, and permission to view owner data
Route::get('/gestione', function () {
    return view('manage'); // Return the manage view
})->middleware(['auth', 'verified', 'permission:view ownerdata'])->name('manage'); // Apply necessary middleware

/*********************************
 ********************************* 
 * Roles Routes
 *********************************
 *********************************/

// Define a route for showing the add role form, requiring authentication, verification, and permission to publish owner data
Route::get('/gestione/ruoli/aggiungi', [RolesController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.add'); // Name the route

// Define a route for storing a new role, requiring authentication, verification, and permission to publish owner data
Route::post('/gestione/ruoli/salva', [RolesController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('roles.store'); // Name the route

// Define a route for managing roles, requiring authentication, verification, and permission to view owner data
Route::get('/gestione/ruoli', [RolesController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('roles'); // Name the route

// Define a route for editing roles, requiring authentication, verification, and permission to edit owner data
Route::get('/gestione/ruoli/modifica', [RolesController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/gestione/ruoli', [RolesController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('roles.update'); // Name the route

// Define a route for deleting a role, requiring authentication, verification, and permission to remove owner data
Route::delete('/gestione/gestione-ruoli', [RolesController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('roles.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions Routes
 *********************************
 *********************************/

// Define a route for showing the add permission form, requiring authentication, verification, and permission to publish owner data
Route::get('/gestione/permessi/aggiungi', [PermissionsController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata'])
    ->name('permissions.add'); // Apply necessary middleware

// Define a route for storing a new permission, requiring authentication, verification, and permission to publish owner data
Route::post('/gestione/permessi/salva', [PermissionsController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish ownerdata']) // Apply necessary middleware
    ->name('permissions.store'); // Name the route

// Define a route for managing permission, requiring authentication, verification, and permission to view owner data
Route::match(['get', 'post'], '/gestione/permessi', [PermissionsController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions'); // Name the route

// Define a route for editing permission, requiring authentication, verification, and permission to edit owner data
Route::get('/gestione/permessi/modifica', [PermissionsController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.edit'); // Name the route

// Define a route for updating a permission, requiring authentication, verification, and permission to edit owner data
Route::post('/gestione/permessi', [PermissionsController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata']) // Apply necessary middleware
    ->name('permissions.update'); // Name the route

// Define a route for deleting a permission, requiring authentication, verification, and permission to remove owner data
Route::delete('/gestione/permessi', [PermissionsController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove ownerdata']) // Apply necessary middleware
    ->name('permissions.delete'); // Name the route

/*********************************
 ********************************* 
 * Permissions and Roles Sync Routes
 *********************************
 *********************************/

// Define a route for viewing and posting to the edit permissions for roles page, requiring authentication, verification, and permission to edit owner data
Route::get('/gestione/assegnazione-permessi/modifica', [PermissionsAssignerController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner'); // Name the route

// Define a route for storing updates to permissions for roles, requiring authentication, verification, and permission to edit owner data
Route::post('/gestione/assegnazione-permessi/modifica', [PermissionsAssignerController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata|remove ownerdata']) // Apply necessary middleware
    ->name('permissions-assigner.store'); // Name the route

/*********************************
 ********************************* 
 * Users Routes
 *********************************
 *********************************/

Route::get('/gestione/utenti', [UsersController::class, 'view'])
    ->middleware(['auth', 'verified', 'permission:view users'])
    ->name('users');

Route::match(['get', 'put'], '/gestione/utenti/modifica/{user_id}', [UsersController::class, 'edit'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.edit');

Route::post('/gestione/utenti/aggiorna/{user_id}', [UsersController::class, 'update'])
    ->middleware(['auth', 'verified', 'permission:edit users'])
    ->name('users.update');

Route::match(['get', 'post'], '/gestione/utenti/aggiungi', [UsersController::class, 'add'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.add');

Route::post('/gestione/utenti/salva', [UsersController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:publish users'])
    ->name('users.store');

Route::match(['get', 'delete'], '/gestione/utenti/elimina/{user_id}', [UsersController::class, 'delete'])
    ->middleware(['auth', 'verified', 'permission:remove users'])
    ->name('users.delete');

/*********************************
 ********************************* 
 * Products Routes
 *********************************
 *********************************/

Route::middleware(['auth', 'verified'])->group(function () {
    // Lista prodotti
    Route::get('/prodotti', [ProductsController::class, 'index'])
        ->middleware('permission:view products')
        ->name('products');

    // Aggiungi prodotto
    Route::get('/prodotti/aggiungi', [ProductsController::class, 'create'])
        ->middleware('permission:publish products')
        ->name('product.add');

    // Modifica prodotto
    Route::get('/prodotti/modifica/{product}', [ProductsController::class, 'edit'])
        ->middleware('permission:edit products')
        ->name('product.edit');

    // Salva nuovo prodotto
    Route::post('/prodotti/salva', [ProductsController::class, 'store'])
        ->middleware('permission:publish products')
        ->name('product.store');

    // Aggiorna prodotto esistente
    Route::put('/prodotti/aggiorna/{product}', [ProductsController::class, 'update'])
        ->middleware('permission:edit products')
        ->name('product.update');

    // Elimina prodotto
    Route::get('/prodotti/elimina/{product}', [ProductsController::class, 'destroy'])
        ->middleware('permission:remove products')
        ->name('product.delete');

    // Visualizza singolo prodotto
    Route::get('/prodotti/{product}', [ProductsController::class, 'show'])
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

Route::post('/logs/pulisci', [LogsController::class, 'clearLaravelLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.clear');

Route::get('/logs/worker', [LogsController::class, 'worker'])
    ->middleware(['auth', 'verified', 'permission:view ownerdata'])
    ->name('logs.worker');

Route::post('/logs/worker/pulisci', [LogsController::class, 'clearWorkerLog'])
    ->middleware(['auth', 'verified', 'permission:edit ownerdata'])
    ->name('logs.worker.clear');

/*********************************
 ********************************* 
 * Profile Routes Managed by Breeze
 *********************************
 *********************************/

// Group routes that require authentication and user plan check
Route::middleware(['auth', CheckUserPlanAndRole::class])->group(function () {
    Route::get('/profilo', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profilo', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profilo', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/profilo/abbonamento', [SubscriptionProfileController::class, 'subscription'])
        ->name('profile.subscription');

});

Route::get('/profilo/azienda/{user}', [CompanyDataController::class, 'edit'])
    ->name('company.edit');

Route::patch('/profilo/azienda/{user}/store', [CompanyDataController::class, 'createOrUpdate'])
    ->name('company.update');

/*********************************
 ********************************* 
 * Stripe Routes
 *********************************
 *********************************/

Route::get('/abbonamenti/tutti', [SubscriptionController::class, 'showAllSubscriptionsOfAllUsers'])
    ->middleware(['auth', 'verified', 'permission:view subscriptions'])
    ->name('subscriptions.all');

Route::get('/abbonamenti/modifica/{id}', [SubscriptionController::class, 'showEditForm'])
    ->middleware(['auth', 'verified', 'permission:view subscriptions'])
    ->name('subscriptions.edit');

Route::patch('/abbonamenti/modifica/{id}', [SubscriptionController::class, 'updateSubscription'])
    ->middleware(['auth', 'verified', 'permission:edit subscriptions'])
    ->name('subscriptions.update');

Route::get('/abbonamenti/visualizza/{id}', [SubscriptionController::class, 'showSubscriptionByStripeId'])
    ->middleware(['auth', 'verified', 'permission:view subscriptions'])
    ->name('subscriptions.view');

// Require the authentication routes defined in auth.php
require __DIR__.'/auth.php';
