<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Login;
use App\Livewire\Home;
use App\Livewire\Inventory;
use App\Livewire\PendingApproval;
use App\Livewire\DeviceHistory;
use App\Livewire\InventoryAnalytics;
use App\Livewire\CasualWorkforce;
use App\Livewire\ManageUser;
use App\Livewire\ManageRoles;
use App\Livewire\ManagePermissions;
use App\Livewire\OffboardingOverview;
use App\Livewire\Onboarding;
use App\Livewire\ManageRequisition;
use App\Livewire\HrCasualManagement;
use App\Livewire\HrmCasualManagement;
use App\Livewire\OnboardNewUser;
use App\Livewire\ContinueOnboarding;
use App\Livewire\DocumentsManagement;
use App\Livewire\VideoManagement;
use App\Livewire\UserDocuments;
use App\Livewire\PrintersAndTonner;
use App\Livewire\DtcPayment;
use App\Livewire\Departments;
use App\Livewire\Designations;
use App\Livewire\Divisions;
use App\Http\Controllers\ContractController;
use App\Livewire\EditProfile;
use App\Livewire\Coo\RequisitionApproval;
use App\Livewire\ForgotPassword;
use App\Livewire\ResetPassword;
use App\Livewire\MpesaAnalytics;


Route::get('/', Login::class)->name('login');
Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

//routes for authenticated users
Route::middleware(['auth'])->group(function(){


Route::get('/offboarding', OffboardingOverview::class)
    // ->middleware('permission:view all offboarding')
    ->name('offboarding.index');


// HR and SuperAdmin only
Route::middleware(['role:Hr|SuperAdmin'])->group(function () {
   Route::get('/hr/casual/dashboard', HrCasualManagement::class)->name('hr.casual.manage');
   Route::get('/contracts/print/{requisitionId}',[ContractController::class, 'print'])->name('contracts.print');

   //onboarding and offboarding
   Route::get('/onboard-new-user/{onboardingId?}',OnboardNewUser::class)->name('onboard-new-user');
   Route::get('/continue-onboarding', ContinueOnboarding::class)->name('continue-onboarding');
   Route::get('/documents-management', DocumentsManagement::class)->name('documents-management');
   Route::get('/video-management', VideoManagement::class)->name('video-management');
   Route::get('/users-documents', UserDocuments::class)->name('users-documents');
   Route::get('/onboarding', Onboarding::class)->name('onboarding');
});

// HRM and SuperAdmin only
Route::middleware(['role:Hrm|SuperAdmin|Hrm_delegate'])->group(function () {
   Route::get('/hrm/casual/dashboard', HrmCasualManagement::class)->name('hrm.casual.manage');
});

Route::middleware(['role:Hr|It'])->group(function () {
   Route::get('/designations', Designations::class)->name('designations');
   Route::get('/departments', Departments::class)->name('departments');
   Route::get('/divisions', Divisions::class)->name('divisions');
   Route::get('/usermanagement', ManageUser::class)->name('usermanagement');
});


// Coo and SuperAdmin only
Route::middleware(['role:Coo|SuperAdmin'])->group(function () {
    Route::get('/coo-approval', RequisitionApproval::class)->name('coo-approval');
});


// IT and SuperAdmin only
Route::middleware(['role:It|SuperAdmin'])->group(function () {
   Route::get('/inventory', Inventory::class)->name('inventory');
   Route::get('/devicehistory', DeviceHistory::class)->name('devicehistory');
   Route::get('/inventory-analytics', InventoryAnalytics::class)->name('inventory-analytics');
   Route::get('/rolemanagement', ManageRoles::class)->name('rolemanagement');
   Route::get('/permissionmanagement', ManagePermissions::class)->name('permissionmanagement');
   Route::get('/printers-tonner', PrintersAndTonner::class)->name('printers-tonner');
});


// Finance and SuperAdmin only
Route::middleware(['role:Finance|SuperAdmin'])->group(function () {
    Route::get('/dtc-payment', DtcPayment::class)->name('dtc-payment');
    Route::get('/mpesa-analytics', MpesaAnalytics::class)->name('mpesa-analytics');
});

// Finance and SuperAdmin only
Route::middleware(['role:Hod|SuperAdmin'])->group(function () {
   Route::get('/pendingapproval', PendingApproval::class)->name('pendingapproval');
   Route::get('/requisition', ManageRequisition::class)->name('requisition');
});

 // All authenticated users
Route::get('/casual-workforce', CasualWorkforce::class)->name('casual-workforce');
Route::get('/edit-profile', EditProfile::class)->name('edit-profile');
Route::get('/home', Home::class)->name('home');
});