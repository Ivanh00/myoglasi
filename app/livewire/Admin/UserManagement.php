<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterStatus = 'all';
    
    public $selectedUser = null;
    public $showEditModal = false;
    public $showBanModal = false;
    public $showBalanceModal = false;
    public $showUserDetailModal = false;

    public $editState = [
        'name' => '',
        'email' => '',
        'city' => '',
        'phone' => '',
        'phone_visible' => false,
        'is_admin' => false,
    ];

    public $banState = [
        'ban_reason' => '',
    ];

    public $balanceState = [
        'amount' => 0,
        'description' => '',
    ];

    public $userDetails = [];

    public function mount()
    {
        $this->resetModals();
    }

    public function resetModals()
    {
        $this->showEditModal = false;
        $this->showBanModal = false;
        $this->showBalanceModal = false;
        $this->showUserDetailModal = false;
        $this->selectedUser = null;
    }

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
        $this->resetModals(); // Zatvori sve druge modale
        $this->selectedUser = User::find($userId);
        $this->editState = [
            'name' => $this->selectedUser->name,
            'email' => $this->selectedUser->email,
            'city' => $this->selectedUser->city,
            'phone' => $this->selectedUser->phone,
            'phone_visible' => $this->selectedUser->phone_visible,
            'is_admin' => $this->selectedUser->is_admin,
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
        ]);

        $this->selectedUser->update($validated['editState']);
        $this->resetModals();
        $this->dispatch('notify', type: 'success', message: 'Korisnik uspešno ažuriran!');
    }

    public function closeAllModals()
    {
        $this->resetModals();
    }

    public function banUser($userId)
    {
        $this->resetModals(); // Zatvori sve druge modale
        $this->selectedUser = User::find($userId);
        $this->banState = ['ban_reason' => ''];
        $this->showBanModal = true;
    }

    public function confirmBan()
    {
        $this->validate([
            'banState.ban_reason' => 'required|string|max:500',
        ]);

        $this->selectedUser->update([
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => $this->banState['ban_reason'],
        ]);

        $this->resetModals();
        $this->dispatch('notify', type: 'success', message: 'Korisnik je uspešno banovan!');
    }

    public function unbanUser($userId)
    {
        $user = User::find($userId);
        $user->update([
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Korisnik je uspešno odbanovan!');
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        
        if ($user->is_admin && $user->id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati admin korisnika!');
            return;
        }

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Ne možete obrisati sebe!');
            return;
        }

        // Delete related data
        $user->listings()->delete();
        $user->transactions()->delete();
        $user->sentMessages()->delete();
        $user->receivedMessages()->delete();
        $user->favorites()->delete();

        $user->delete();
        $this->dispatch('notify', type: 'success', message: 'Korisnik i svi njegovi podaci su uspešno obrisani!');
    }

    public function adjustBalance($userId)
    {
        $this->resetModals(); // Zatvori sve druge modale
        $this->selectedUser = User::find($userId);
        $this->balanceState = [
            'amount' => 0,
            'description' => '',
        ];
        $this->showBalanceModal = true;
    }

    public function updateBalance()
    {
        $this->validate([
            'balanceState.amount' => 'required|numeric',
            'balanceState.description' => 'required|string|max:255',
        ]);

        $amount = $this->balanceState['amount'];
        $description = $this->balanceState['description'];

        // Update user balance
        $this->selectedUser->increment('balance', $amount);

        // Create transaction record
        Transaction::create([
            'user_id' => $this->selectedUser->id,
            'amount' => $amount,
            'type' => $amount > 0 ? 'admin_credit' : 'admin_debit',
            'description' => $description . ' (Admin akcija)',
        ]);

        $this->resetModals();
        $this->dispatch('notify', type: 'success', message: 'Balans je uspešno ažuriran!');
    }

    public function viewUserDetails($userId)
    {
        $this->resetModals(); // Zatvori sve druge modale
        $user = User::with(['listings', 'transactions', 'sentMessages', 'receivedMessages', 'favorites'])
            ->find($userId);

        $this->userDetails = [
            'user' => $user,
            'total_listings' => $user->listings->count(),
            'active_listings' => $user->listings->where('status', 'active')->count(),
            'total_spent' => $user->transactions->where('amount', '<', 0)->sum('amount') * -1,
            'total_received' => $user->transactions->where('amount', '>', 0)->sum('amount'),
            'messages_sent' => $user->sentMessages->count(),
            'messages_received' => $user->receivedMessages->count(),
            'favorites_count' => $user->favorites->count(),
            'recent_listings' => $user->listings()->latest()->take(5)->get(),
            'recent_transactions' => $user->transactions()->latest()->take(10)->get(),
        ];

        $this->showUserDetailModal = true;
    }

    public function render()
    {
        $users = User::query()
            ->withCount(['listings', 'transactions'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== 'all', function ($query) {
                if ($this->filterStatus === 'banned') {
                    $query->where('is_banned', true);
                } elseif ($this->filterStatus === 'admin') {
                    $query->where('is_admin', true);
                } elseif ($this->filterStatus === 'with_balance') {
                    $query->where('balance', '>', 0);
                } elseif ($this->filterStatus === 'active') {
                    $query->where('is_banned', false);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.user-management', compact('users'))
            ->layout('layouts.admin');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->resetPage();
    }
}