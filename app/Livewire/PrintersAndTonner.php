<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Device;
use App\Models\Printer;
use App\Models\Toner;

class PrintersAndTonner extends Component
{

public $activeCategory = 'HQ Printers'; // Default category on page load
protected $queryString = ['activeCategory', 'search', 'actionFilter'];


public $showPrinterLocationModal = false;
public $search = '';
public $actionFilter = 'all'; // 'all', 'assigned', 'returned'
public $office_location = '';
  
  
//variables for toner details
public $brand_name = '';
public $toner_model = '';
public $toner_color = '';
public $toner_stock = '';

public $selectedToner ='';


//to be checked if needed
public $selectedPrinterId = null;
public $selectedPrinterName = null;
public $printerModel = null;
public $printerTagNumber = null;
public $currentLocation = null;
public $tonerSearch = '';
public $filteredToners = [];
public $selectedToners = [];
public $printerId;





    public function editPrinterLocation($printerId)
    {
        $this->selectedPrinterId = $printerId;
        $printer = Device::find($printerId);
        $this->selectedToners = [];
        $this->selectedPrinterName = $printer->name;
        $this->printerModel = $printer->model;
        $this->printerTagNumber = $printer->tag_number;
        $this->office_location = $printer->printer->office_location ?? 'Not set';
    
        $this->showPrinterLocationModal = true;
   
    }

    public function updatedTonerSearch()
{
    $this->filteredToners = Toner::query()
        ->when($this->tonerSearch, function ($query) {
            $query->where('brand_name', 'like', '%' . $this->tonerSearch . '%')
                  ->orWhere('model', 'like', '%' . $this->tonerSearch . '%')
                  ->orWhere('color', 'like', '%' . $this->tonerSearch . '%');
        })
        ->limit(2)
        ->get()
        ->toArray();
}

public function addToner($tonerId)
{
    if (!in_array($tonerId, $this->selectedToners)) {
        $this->selectedToners[] = $tonerId;
    }

    $this->tonerSearch = '';
    $this->filteredToners = [];
}

public function removeToner($tonerId)
{
    $this->selectedToners = array_filter(
        $this->selectedToners,
        fn($id) => $id != $tonerId
    );
}
    
public function updatePrinterLocation()
{
    $this->validate([
        'office_location' => 'required|string|max:255',
    ]);

    // Create or update printer
    $printer = Printer::updateOrCreate(
        [
            'device_id' => $this->selectedPrinterId,
        ],
        [
            'office_location' => strtoupper($this->office_location),
        ]
    );

    // Attach compatible toners
    if (!empty($this->selectedToners)) {
        $printer->toners()->sync($this->selectedToners);
    }

    // Notification
    $this->dispatch(
        'notify',
        type: 'success',
        title: 'Location Updated',
        message: 'Printer location and compatible toners updated successfully!'
    );

    // Reset fields if needed
    $this->reset([
        'tonerSearch',
        'filteredToners',
    ]);

    // Close modal
    $this->showPrinterLocationModal = false;
}

public function decreaseStock($id)
{
    $toner = Toner::findOrFail($id);

    if ($toner->stock > 0) {
        $toner->decrement('quantity');
    }
}

public function increaseStock($id)
{
    $toner = Toner::findOrFail($id);
    $toner->increment('quantity');
}

public $selectedTonerId;
public $adjustmentType; // 'add' or 'subtract'
public $adjustQuantity = 1;
public $showStockModal = false;

public function openStockModal($tonerId, $type)
{
    $this->selectedTonerId = $tonerId;
    $this->adjustmentType = $type;
    $this->adjustQuantity = 1;
    $this->showStockModal = true;
}

public function updateStock()
{
    $toner = Toner::findOrFail($this->selectedTonerId);

    if ($this->adjustmentType === 'add') {
        $toner->increment('quantity', $this->adjustQuantity);
    } else {
        $toner->decrement('quantity', $this->adjustQuantity);

        if ($toner->stock < 0) {
            $toner->update(['stock' => 0]);
        }
    }

    $this->showStockModal = false;

    $this->dispatch('notify',
        type: 'success',
        title: 'Quantity Updated',
        message: 'Toner Quantity updated successfully'
    );
}


    public function createToner()
    {

        // Validation logic for creating a toner
        $this->validate([
            'brand_name' => 'required|string|max:255',
            'toner_model' => 'required|string|max:255',
            'toner_color' => 'required|string|max:255',
            'toner_stock' => 'required|integer|min:0',
            // Add more validation rules as needed
        ]);

        // Logic to create a new toner record in the database
        Toner::create([
            'brand_name' => strtoupper($this->brand_name),
            'model' => strtoupper($this->toner_model),
            'color' => $this->toner_color,
            'quantity' => $this->toner_stock,
            // Add more fields as needed
        ]);

        // Flash message for Livewire UI
        $this->dispatch('notify', 
                type: 'success',
                title: 'Toner Created',
                message: "Toner created successfully."
        );

        // Reset form fields after creation
        $this->reset(['brand_name', 'toner_model', 'toner_color', 'toner_stock']);

        $this->dispatch('close-device-modal'); // Custom event to refresh toner list if needed
    }

    public function getCategoryTitle()
    {
        return match($this->activeCategory) {
            'HQ Printers' => ['title' => 'HQ Printers', 'subtitle' => 'All devices currently tracked'],
            'tatu city printers' => ['title' => 'Tatu City Printers', 'subtitle' => 'Printers in Tatu City'],
            'mombasa printers' => ['title' => 'Mombasa Printers', 'subtitle' => 'Printers in Mombasa'],
            'c-suite printers' => ['title' => 'C-Suite Printers', 'subtitle' => 'Printers in C-Suite'],
            'mfi printers' => ['title' => 'MFI Printers', 'subtitle' => 'MFI Printers'],
            'toners' => ['title' => 'Toner Management', 'subtitle' => 'Manage toner inventory'],
            'drum units' => ['title' => 'Drum Units', 'subtitle' => 'Manage drum unit inventory'],
            default => ['title' => 'Device Inventory', 'subtitle' => 'All devices currently tracked'],
        };
    }


     // Fix: Change from getPrintersProperty() to printers() method
    public function getPrintersProperty()
    {
        $query = Device::query()
            ->with(['user','printer.toners', 'issues' => function($q) {
                $q->where('status', 'Active')->latest();
            }])
            ->where('type', 'printer'); // Base query for printers only

        // Apply category filters
        if ($this->activeCategory === 'HQ Printers') { // Fixed case sensitivity
            $query->where('branch', 'HQ');
        } elseif ($this->activeCategory === 'tatu city printers') {
            $query->where('branch', 'Tatu-city');
        } elseif ($this->activeCategory === 'mombasa printers') {
            $query->where('branch', 'Mombasa');
        } elseif ($this->activeCategory === 'c-suite printers') {
            $query->where('branch', 'C-Suite');
        } elseif ($this->activeCategory === 'mfi printers') {
            $query->where('branch', 'MFI');
        } elseif ($this->activeCategory === 'printers with issues') {
            $query->whereHas('issues', function($q) {
                $q->where('status', 'Active');
            });
        }

        // Apply search filter if exists
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('tag_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('printer', function($q) {
                      $q->where('office_location', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply action filter if needed
        if ($this->actionFilter === 'assigned') {
            $query->whereNotNull('user_id');
        } elseif ($this->actionFilter === 'returned') {
            $query->whereNull('user_id');
        }

        return $query->paginate(5);
    }



    public function getTonersProperty()
    {
        $query = Toner::query()
            ->with(['printers' => function($q) {
                $q->with('device');
            }]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('brand_name', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('color', 'like', '%' . $this->search . '%');
            });
        }
            
        return $query->paginate(5);
        
    }

 
    public function editPrinter($printerId){
    $this->showDeviceModal = true;
    }

    public function editToner(){
        dd('we are here');
    }


    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.printers-and-tonner', [
            'Printers' => $this->printers,
            'toners' => $this->toners,
        ]);
    }
}
