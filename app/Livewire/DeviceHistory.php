<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\AssignDeviceLog;
use Livewire\WithPagination;
use App\Exports\DeviceHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class DeviceHistory extends Component
{

     use WithPagination;

    public string $search = '';
    public string $actionFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingActionFilter() { $this->resetPage(); }

    #[layout('layouts.dashboard')]
    public function render()
    {
        $logs = AssignDeviceLog::with(['device', 'user', 'actionByUser'])
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->whereHas('device', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                      ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                      ->orWhereHas('actionByUser', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                      ->orWhere('reason', 'like', "%{$this->search}%")
                      ->orWhere('comment', 'like', "%{$this->search}%");
                });
            })
            ->when($this->actionFilter, fn($q) => $q->where('action_type', $this->actionFilter))
            ->latest('action_date')
            ->paginate(15);

        return view('livewire.device-history', ['logs' => $logs]);
    }

    public function export($format = 'xlsx')
    {
        
        $filename = 'device_history_' . Str::slug(now()) . '.' . $format;

        $type = $format === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;

        return Excel::download(
            new DeviceHistoryExport($this->search ?: null),
            $filename,
            $type
        );
    }
    

}
