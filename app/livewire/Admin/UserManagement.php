<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleAdmin($userId)
    {
        $user = User::find($userId);
        $user->is_admin = !$user->is_admin;
        $user->save();

        $this->dispatch('notify', type: 'success', message: 'Korisnik ažuriran!');
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        
        if ($user->is_admin) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati admina!');
            return;
        }

        $user->delete();
        $this->dispatch('notify', type: 'success', message: 'Korisnik obrisan!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.user-management', compact('users'))
            ->layout('layouts.admin');
    }
}