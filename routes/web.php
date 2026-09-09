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


Route::get('/', Login::class)->name('login');


//routes for authenticated users
Route::middleware(['auth'])->group(function(){


 Route::get('/home', Home::class)->name('home');


 Route::get('/offboarding', OffboardingOverview::class)
    // ->middleware('permission:view all offboarding')
    ->name('offboarding.index');


 Route::get('/inventory', Inventory::class)->name('inventory');
 Route::get('/pendingapproval', PendingApproval::class)->name('pendingapproval');
 Route::get('/devicehistory', DeviceHistory::class)->name('devicehistory');
 Route::get('/inventory-analytics', InventoryAnalytics::class)->name('inventory-analytics');
 Route::get('casual-workforce', CasualWorkforce::class)->name('casual-workforce');
 Route::get('/usermanagement', ManageUser::class)->name('usermanagement');
 Route::get('/rolemanagement', ManageRoles::class)->name('rolemanagement');
 Route::get('/permissionmanagement', ManagePermissions::class)->name('permissionmanagement');
 Route::get('/onboarding', Onboarding::class)->name('onboarding');
 Route::get('requisition', ManageRequisition::class)->name('requisition');
 Route::get('/hr/casual/dashboard', HrCasualManagement::class)->name('hr.casual.manage');
 Route::get('/hrm/casual/dashboard', HrmCasualManagement::class)->name('hrm.casual.manage');
 Route::get('/onboard-new-user/{onboardingId?}',OnboardNewUser::class)->name('onboard-new-user');
 Route::get('/continue-onboarding', ContinueOnboarding::class)->name('continue-onboarding');
 Route::get('/documents-management', DocumentsManagement::class)->name('documents-management');
 Route::get('/video-management', VideoManagement::class)->name('video-management');
 Route::get('/users-documents', UserDocuments::class)->name('users-documents');
 Route::get('/printers-tonner', PrintersAndTonner::class)->name('printers-tonner');
 Route::get('/dtc-payment', DtcPayment::class)->name('dtc-payment');
 Route::get('/departments', Departments::class)->name('departments');
 Route::get('/designations', Designations::class)->name('designations');
 Route::get('/divisions', Divisions::class)->name('divisions');
 Route::get('/contracts/print/{requisitionId}',[ContractController::class, 'print'])->name('contracts.print');
 Route::get('edit-profile', EditProfile::class)->name('edit-profile');
});