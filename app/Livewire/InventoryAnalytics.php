<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Device;
use App\Models\User;

#[Layout('layouts.dashboard')]
class InventoryAnalytics extends Component
{
    use WithPagination;

    protected $listeners = ['refresh-devices' => 'refreshParent'];
    
    public function refreshParent()
    {
        $this->getDevicesProperty();
    }

    // Public properties
    public $search = '';
    public $actionFilter = '';
    public $activeCategory = 'all';
    
    // Stats properties
    public $deviceCount;
    public $assignedDevicesCount;
    public $inactiveDevicesCount;
    public $unAssignedDevicesCount;
    public $activeDevicesWithIssuesCount;
    public $inactiveDevicesWithIssuesCount;
    public $activeDevicesWithoutIssuesCount;
    
    // Modal states
    public $showDeviceModal = false;
    public $showReportModal = false;
    public $showDeleteModal = false;
    public $deviceModalMode = 'add';
    public $deleteId = null;
    public $reportCategory = '';
    public $reportTitle = '';
    public $reportContent = '';
    public $branch = 'HQ'; // Default branch for new devices
    
    // Form properties
    public $deviceForm = [
        'id' => null,
        'name' => '',
        'type' => 'desktop',
        'model' => '',
        'serialNumber' => '',
        'status' => 'active',
        'location' => '',
        'assignedTo' => '',
        'purchaseDate' => '',
        'warrantyUntil' => '',
        'notes' => ''
    ];

    // Dummy data for accessories, toners, printers (replace with actual models)
    public $accessories = [];
    public $toners = [];
    public $printers = [];

    // Query string parameters
    protected $queryString = ['activeCategory', 'search', 'actionFilter'];

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function updatingActiveCategory()
    {
        $this->resetPage();
    }

    // Load initial data
    public function mount()
    {
        $this->loadDummyData();
    }

    protected function loadDummyData()
    {
        $this->accessories = [
            (object) ['id' => 1, 'name' => 'Wireless Mouse', 'model' => 'MX Master 3', 'compatibility' => 'All USB devices', 'stock' => 156, 'lastRestocked' => '2024-03-15'],
            (object) ['id' => 2, 'name' => 'Mechanical Keyboard', 'model' => 'K70 RGB', 'compatibility' => 'PC/Laptop', 'stock' => 89, 'lastRestocked' => '2024-03-10'],
            (object) ['id' => 5, 'name' => 'Docking Station', 'model' => 'USB-C Universal', 'compatibility' => 'USB-C laptops', 'stock' => 12, 'lastRestocked' => '2024-02-15'],
        ];

        $this->toners = [
            (object) ['id' => 1, 'name' => 'Black Toner', 'model' => 'HP 206A', 'colorClass' => 'bg-black', 'stock' => 24, 'stockPercentage' => 48, 'compatiblePrinters' => ['HP M254dw', 'HP M281fdw']],
            (object) ['id' => 2, 'name' => 'Cyan Toner', 'model' => 'HP 206A', 'colorClass' => 'bg-cyan-600', 'stock' => 18, 'stockPercentage' => 36, 'compatiblePrinters' => ['HP M254dw', 'HP M281fdw']],
            (object) ['id' => 5, 'name' => 'High Yield Black', 'model' => 'Brother TN-760', 'colorClass' => 'bg-black', 'stock' => 22, 'stockPercentage' => 44, 'compatiblePrinters' => ['Brother MFC-L2750DW']],
        ];

        $this->printers = [
            (object) ['id' => 1, 'name' => 'HP LaserJet Pro', 'model' => 'M254dw', 'location' => 'Floor 1 - HR', 'status' => 'online', 'compatibleToners' => ['HP 206A', 'HP 207A'], 'pagesPrinted' => '15,234', 'tonerLevel' => 65],
            (object) ['id' => 2, 'name' => 'HP Color LaserJet', 'model' => 'M281fdw', 'location' => 'Floor 2 - Marketing', 'status' => 'online', 'compatibleToners' => ['HP 206A', 'HP 207A'], 'pagesPrinted' => '28,456', 'tonerLevel' => 42],
            (object) ['id' => 4, 'name' => 'Canon imageCLASS', 'model' => 'MF445dw', 'location' => 'Floor 4 - Executive', 'status' => 'online', 'compatibleToners' => ['Canon 057H', 'Canon 057'], 'pagesPrinted' => '8,234', 'tonerLevel' => 82],
        ];
    }

    public function getCategoryTitle()
    {
        return match($this->activeCategory) {
            'all' => ['title' => 'Device Inventory', 'subtitle' => 'All devices currently tracked'],
            'active devices with issues' => ['title' => 'Active Devices with issues', 'subtitle' => 'Assigned devices needing attention'],
            'inactive devices with issues' => ['title' => 'Inactive Devices with issues', 'subtitle' => 'Unassigned devices needing attention'],
            'unassigned devices in good condition' => ['title' => 'Unassigned Devices in good condition', 'subtitle' => 'Devices not currently assigned but in good condition'],
            'archived devices' => ['title' => 'Archived Devices', 'subtitle' => 'Devices that have been archived'],
            'printers' => ['title' => 'Printers Management', 'subtitle' => 'Oversee printer fleet and supplies'],
            default => ['title' => 'Device Inventory', 'subtitle' => 'All devices currently tracked'],
        };
    }

    public function getDevicesProperty()
    {
        $query = Device::query()
        ->with(['user', 'latestAssignmentLog.previousUser','issues' => function($q) {
            $q->where('status', 'Active')->latest(); // Get active issues, most recent first
        }]);
        
        // Apply category filter based on your schema
        if ($this->activeCategory === 'active devices with issues') {
            // Active devices: assigned (user_id not null) AND in good condition
            $query->whereNotNull('user_id')->where('good_condition', false);
        } elseif ($this->activeCategory === 'inactive devices with issues') {
            // Inactive devices: not assigned OR not in good condition
            $query->where(function($q) {
                $q->whereNull('user_id')->Where('good_condition', false);
            });
        } elseif ($this->activeCategory === 'unassigned devices in good condition') {
            // Unassigned devices in good condition: not assigned AND in good condition
            $query->whereNull('user_id')->where('good_condition', true);
        }elseif ($this->activeCategory === 'archived devices') {
            // Archived devices: onlyTrashed
            $query->onlyTrashed();
        }
        
        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                  ->orWhere('tag_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        // Apply action filter
        if ($this->actionFilter === 'assigned') {
            $query->whereNotNull('user_id');
        } elseif ($this->actionFilter === 'returned') {
            $query->whereNull('user_id');
        }
        
        return $query->paginate(5);
    }

    public function getDeviceCountsProperty()
    {
        return [
            'desktops' => Device::where('type', 'desktop')->count(),
            'laptops' => Device::where('type', 'laptop')->count(),
            'printers' => Device::where('type', 'printer')->count(),
            'mobile' => Device::whereIn('type', ['mobile', 'tablet'])->count(),
        ];
    }

    // Device CRUD Operations
    public function openAddDeviceModal()
    {
        $this->deviceModalMode = 'add';
        $this->deviceForm = [
            'id' => null,
            'name' => '',
            'type' => 'desktop',
            'model' => '',
            'serialNumber' => '',
            'status' => 'active',
            'location' => '',
            'assignedTo' => '',
            'purchaseDate' => '',
            'warrantyUntil' => '',
            'notes' => ''
        ];
        $this->showDeviceModal = true;
    }

    public function editDevice($deviceId)
    {
        $device = Device::findOrFail($deviceId);
        $this->deviceModalMode = 'edit';
        $this->deviceForm = [
            'id' => $device->id,
            'name' => $device->name,
            'type' => $device->type,
            'model' => $device->model,
            'serialNumber' => $device->serial_number,
            'status' => $device->status,
            'location' => $device->location,
            'assignedTo' => $device->user_id,
            'purchaseDate' => $device->purchase_date,
            'warrantyUntil' => $device->warranty_until,
            'notes' => $device->notes
        ];
        $this->showDeviceModal = true;
    }

    public function saveDevice()
    {
        if ($this->deviceModalMode === 'add') {
            Device::create([
                'name' => $this->deviceForm['name'],
                'type' => $this->deviceForm['type'],
                'model' => $this->deviceForm['model'],
                'serial_number' => $this->deviceForm['serialNumber'],
                'status' => $this->deviceForm['status'],
                'location' => $this->deviceForm['location'],
                'user_id' => $this->deviceForm['assignedTo'] ?: null,
                'purchase_date' => $this->deviceForm['purchaseDate'],
                'warranty_until' => $this->deviceForm['warrantyUntil'],
                'notes' => $this->deviceForm['notes']
            ]);
            session()->flash('message', 'Device added successfully!');
        } else {
            $device = Device::findOrFail($this->deviceForm['id']);
            $device->update([
                'name' => $this->deviceForm['name'],
                'type' => $this->deviceForm['type'],
                'model' => $this->deviceForm['model'],
                'serial_number' => $this->deviceForm['serialNumber'],
                'status' => $this->deviceForm['status'],
                'location' => $this->deviceForm['location'],
                'user_id' => $this->deviceForm['assignedTo'] ?: null,
                'purchase_date' => $this->deviceForm['purchaseDate'],
                'warranty_until' => $this->deviceForm['warrantyUntil'],
                'notes' => $this->deviceForm['notes']
            ]);
            session()->flash('message', 'Device updated successfully!');
        }
        
        $this->showDeviceModal = false;
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteDevice()
    {
        $device = Device::findOrFail($this->deleteId);
        $device->delete();
        
        session()->flash('message', 'Device deleted successfully!');
        $this->showDeleteModal = false;
        $this->resetPage();
    }

    public function viewDeviceDetails($deviceId)
    {
        $device = Device::with('user', 'issues')->findOrFail($deviceId);
        $this->showDeviceModal = true;
        $this->dispatch('show-device-details', device: $device);
    }

    // Accessory operations
    public function openAddAccessoryModal()
    {
        $this->dispatch('show-add-accessory-modal');
    }

    public function editAccessory($accessoryId)
    {
        $this->dispatch('show-edit-accessory-modal', accessoryId: $accessoryId);
    }

    public function deleteAccessory($accessoryId)
    {
        // Implement accessory deletion
        session()->flash('message', 'Accessory deleted successfully!');
    }

    // Toner operations
    public function openAddTonerModal()
    {
        $this->dispatch('show-add-toner-modal');
    }

    public function editToner($tonerId)
    {
        $this->dispatch('show-edit-toner-modal', tonerId: $tonerId);
    }

    public function deleteToner($tonerId)
    {
        // Implement toner deletion
        session()->flash('message', 'Toner deleted successfully!');
    }

    // Printer operations
    public function openAddPrinterModal()
    {
        $this->dispatch('show-add-printer-modal');
    }

    public function editPrinter($printerId)
    {
        $this->dispatch('show-edit-printer-modal', printerId: $printerId);
    }

    public function viewPrinterDetails($printerId)
    {
        $this->dispatch('show-printer-details', printerId: $printerId);
    }

    // Report operations
    public function openCategoryReport($category)
    {
        $this->reportCategory = $category;
        $this->reportTitle = $this->getReportTitle($category);
        $this->reportContent = $this->generateReportContent($category);
        $this->showReportModal = true;
    }

    protected function getReportTitle($category)
    {
        $titles = [
            'all' => 'Complete Device Inventory Report',
            'active' => 'Active Devices Report',
            'inactive' => 'Inactive Devices Report',
            'accessories' => 'Accessories Inventory Report',
            'toners' => 'Toners Inventory Report',
            'printers' => 'Printers Compatibility Report'
        ];
        return $titles[$category] ?? 'Device Report';
    }

    protected function generateReportContent($category)
    {
        // Implement report generation logic
        return '<div class="p-4">Report content for ' . $category . '</div>';
    }

    public function exportReport($format)
    {
        session()->flash('message', "Exporting {$this->reportTitle} as {$format}");
    }

    public function getStockLevel($stock)
    {
        if ($stock > 50) return 'High';
        if ($stock > 20) return 'Medium';
        return 'Low';
    }

public $users = [];
public string $userSearch = ''; // NEW (for modal)
public $selectedUser;


public function updatedUserSearch()
{
    if (strlen($this->userSearch) < 2) {
        $this->users = [];
        return;
    }

    $this->users = User::select('id', 'name')
        ->where('name', 'like', "%{$this->userSearch}%")
        ->limit(20)
        ->get();
}


public function restoreDevice($deviceId){
Device::withTrashed()->find($deviceId)->restore();

$this->dispatch('notify', 
            type: 'success',
            title: 'Device Restored',
            message: "Device Restored successfully."
);
}

public function openDeviceModal()
    {
        $this->resetForm();

        $this->userSearch = '';
        $this->users = [];
        $this->user_id = null;

        $this->dispatch('open-device-modal');
    }

    public function createDevice()
    {

    //dd($this->selectedUser, $this->name, $this->type, $this->purchase_date, $this->cost, $this->model, strtoupper($this->tag_number), strtoupper($this->serial_number));
    //first do the validation
    $this->validate();

    Device::create([
        'user_id' => $this->selectedUser ?? null,
        'name' => strtoupper($this->name),
        'type' => $this->type,
        'purchase_date' => $this->purchase_date,
        'cost' => $this->cost,
        'model' => strtoupper($this->model),
        'tag_number' => strtoupper($this->tag_number),
        'serial_number' => strtoupper($this->serial_number),
        'branch' => $this->branch ?? 'HQ',
    ]);

    $this->resetForm();
    // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'success',
                title: 'Device Created',
                message: "Device Created successfully."
    );

    $this->dispatch('refresh-devices');

    $this->dispatch('close-device-modal');

     
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['user_id', 'name', 'type', 'purchase_date', 'cost', 'model', 'tag_number', 'serial_number', 'selectedUser', 'userSearch', 'users']);
    }

    public $user_id;
    public $name;
    public $type;
    public $purchase_date;
    public $cost;
    public $model;
    public $tag_number;
    public $serial_number;


    protected $rules = [
        'user_id' => 'nullable|exists:users,id',
        'name' => 'required|string|max:255',
        'type' => 'required|string|min:2',
        'purchase_date' => 'required|date',
        'cost' => 'required|numeric|min:0',
        'model' => 'required|string|max:50',
        'tag_number' => 'required|string|max:50|unique:devices,tag_number',
        'serial_number' => 'required|string|max:50|unique:devices,serial_number',  
    ];

    public function markAsResolved($deviceId, $issueId)
    {
       
        // Implement logic to mark the issue as resolved
        $device = Device::findOrFail($deviceId);
        $device->update(['good_condition' => true]);
        
        $issue = $device->issues()->where('id', $issueId)->firstOrFail();
        $issue->update(['status' => 'Resolved']);
        

        $this->dispatch('notify', 
                type: 'success',
                title: 'Issue Resolved',
                message: 'The issue has been marked as resolved.'
            );
    }

    public function render()
    {
        // Calculate all statistics
        $deviceCount = Device::count();
        

        $assignedDevicesCount = Device::whereNotNull('user_id')
            ->where('line_manager_approval', true)
            ->where('user_accepted', true)
            ->count();

        $inactiveDevicesCount = Device::where('good_condition', false)
            ->whereNull('user_id')
            ->count();
            
        $unAssignedDevicesCount = Device::whereNull('user_id')
            ->where('good_condition', true)
            ->count();
    
        $activeDevicesWithIssuesCount = Device::whereNotNull('user_id')
            ->whereHas('issues', function ($q) {
                $q->where('status', 'Active');
            })->count();

        $inactiveDevicesWithIssuesCount = Device::whereNull('user_id')
            ->whereHas('issues', function ($q) {
                $q->where('status', 'Active');
            })->count();
            
        $activeDevicesWithoutIssuesCount = Device::whereNotNull('user_id')
            ->whereDoesntHave('issues', function ($q) {
                $q->where('status', 'Active');
            })->count();

        $reportCards = [
            [
                'key' => 'active',
                'label' => 'Total Computer Devices',
                'sub' => 'Laptops & Desktops',
                'value' => $deviceCount,
                'trend' => 'Overall Inventory',
                'color' => 'blue',
                'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            ],
            [
                'key' => 'inactive',
                'label' => 'Active With Issues',
                'sub' => 'Devices with issues',
                'value' => $activeDevicesWithIssuesCount,
                'trend' => 'Requires attention',
                'color' => 'rose',
                'icon' => 'M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0L3.16 16.25A2 2 0 005 19z',
            ],
            [
                'key' => 'printers',
                'label' => 'Inactive With Issues',
                'sub' => 'Available in store',
                'value' => $inactiveDevicesWithIssuesCount,
                'trend' => 'Needs repair',
                'color' => 'amber',
                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
            ],
            [
                'key' => 'accessories',
                'label' => 'Unassigned Devices in Good Condition',
                'sub' => 'Across all categories',
                'value' => $unAssignedDevicesCount,
                'trend' => 'Ready to deploy',
                'color' => 'violet',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            ],
            [
                'key' => 'toners',
                'label' => 'Active Devices in Good Condition',
                'sub' => 'All categories combined',
                'value' => $activeDevicesWithoutIssuesCount,
                'trend' => 'All Good',
                'color' => 'emerald',
                'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
            ],
        ];

        return view('livewire.inventory-analytics', [
            'deviceCount' => $deviceCount,
            'assignedDevicesCount' => $assignedDevicesCount,
            'inactiveDevicesCount' => $inactiveDevicesCount,
            'devices' => $this->devices,
            'unAssignedDevicesCount' => $unAssignedDevicesCount,
            'activeDevicesWithIssuesCount' => $activeDevicesWithIssuesCount,
            'inactiveDevicesWithIssuesCount' => $inactiveDevicesWithIssuesCount,
            'activeDevicesWithoutIssuesCount' => $activeDevicesWithoutIssuesCount,
            'deviceCounts' => $this->deviceCounts,
            'reportCards' => $reportCards,
            'accessories' => $this->accessories,
            'toners' => $this->toners,
            'printers' => $this->printers,
            
        ]);
    }
}