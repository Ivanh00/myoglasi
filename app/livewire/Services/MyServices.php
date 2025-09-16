<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class MyServices extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'active';

    protected $queryString = ['search', 'status'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function deleteService($serviceId)
    {
        $service = Service::where('id', $serviceId)
            ->where('user_id', auth()->id())
            ->first();

        if ($service) {
            // Delete associated images
            foreach ($service->images as $image) {
                \Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $service->delete();
            session()->flash('success', 'Usluga je uspešno obrisana.');
        }
    }

    public function toggleStatus($serviceId)
    {
        $service = Service::where('id', $serviceId)
            ->where('user_id', auth()->id())
            ->first();

        if ($service) {
            $service->status = $service->status === 'active' ? 'inactive' : 'active';
            $service->save();
            session()->flash('success', 'Status usluge je promenjen.');
        }
    }

    public function render()
    {
        $query = Service::where('user_id', auth()->id())
            ->with(['category', 'images', 'promotions']);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.services.my-services', [
            'services' => $services
        ])->layout('layouts.app');
    }
}