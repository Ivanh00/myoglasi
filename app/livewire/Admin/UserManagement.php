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
    public $selectedUser = null;
    public $showEditModal = false;

    public $editState = [
        'name' => '',
        'email' => '',
        'city' => '',
        'phone' => '',
        'phone_visible' => false,
        'is_admin' => false,
        'is_banned' => false
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function editUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->editState = [
            'name' => $this->selectedUser->name,
            'email' => $this->selectedUser->email,
            'city' => $this->selectedUser->city,
            'phone' => $this->selectedUser->phone,
            'phone_visible' => $this->selectedUser->phone_visible,
            'is_admin' => $this->selectedUser->is_admin,
            'is_banned' => $this->selectedUser->is_banned ?? false
        ];
        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'editState.name' => 'required|string|max:255',
            'editState.email' => 'required|email|unique:users,email,' . $this->selectedUser->id,
            'editState.city' => 'nullable|string|max:255',
            'editState.phone' => 'nullable|string|max:20',
            'editState.phone_visible' => 'boolean',
            'editState.is_admin' => 'boolean',
            'editState.is_banned' => 'boolean'
        ]);

        $this->selectedUser->update($validated['editState']);
        $this->showEditModal = false;
        $this->dispatch('notify', 'Korisnik uspešno ažuriran!');
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        
        if ($user->is_admin) {
            $this->dispatch('notify', 'Ne možete obrisati admin korisnika!', 'error');
            return;
        }

        $user->delete();
        $this->dispatch('notify', 'Korisnik uspešno obrisan!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.user-management', compact('users'))
            ->layout('layouts.admin');
    }
}