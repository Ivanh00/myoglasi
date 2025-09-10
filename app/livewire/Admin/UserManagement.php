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
    public $filterPaymentPlan = 'all';
    public $filterVerification = 'all';
    
    public $selectedUser = null;
    public $showEditModal = false;
    public $showBanModal = false;
    public $showBalanceModal = false;
    public $showUserDetailModal = false;
    public $showPaymentModal = false;
    public $showVerificationModal = false;

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

    public $paymentState = [
        'payment_plan' => 'per_listing',
        'payment_enabled' => true,
    ];
    
    public $verificationAction = '';
    public $verificationComment = '';

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
        $this->showPaymentModal = false;
        $this->showVerificationModal = false;
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

    public function sendNotificationToUser($userId)
    {
        return redirect()->route('admin.notifications.index', ['user_id' => $userId]);
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
            ->when($this->filterPaymentPlan !== 'all', function ($query) {
                if ($this->filterPaymentPlan === 'free_disabled') {
                    $query->where('payment_enabled', false);
                } elseif ($this->filterPaymentPlan === 'active_plans') {
                    $query->where('payment_enabled', true)
                          ->whereIn('payment_plan', ['monthly', 'yearly'])
                          ->where(function ($q) {
                              $q->whereNull('plan_expires_at')
                                ->orWhere('plan_expires_at', '>', now());
                          });
                } elseif ($this->filterPaymentPlan === 'expired_plans') {
                    $query->where('payment_enabled', true)
                          ->whereIn('payment_plan', ['monthly', 'yearly'])
                          ->where('plan_expires_at', '<', now());
                } else {
                    $query->where('payment_plan', $this->filterPaymentPlan);
                }
            })
            ->when($this->filterVerification !== 'all', function ($query) {
                $query->where('verification_status', $this->filterVerification);
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

    public function updatingFilterPaymentPlan()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->filterPaymentPlan = 'all';
        $this->filterVerification = 'all';
        $this->resetPage();
    }

    // Payment Management Methods
    public function editUserPayment($userId)
    {
        $this->resetModals();
        $user = User::findOrFail($userId);
        $this->selectedUser = $user;
        
        $this->paymentState = [
            'payment_plan' => $user->payment_plan,
            'payment_enabled' => $user->payment_enabled,
            'plan_expires_at' => $user->plan_expires_at ? $user->plan_expires_at->format('Y-m-d') : null,
        ];
        
        $this->showPaymentModal = true;
    }
    
    public function updateUserPayment()
    {
        $this->validate([
            'paymentState.payment_plan' => 'required|in:per_listing,monthly,yearly,free',
            'paymentState.payment_enabled' => 'required|boolean',
            'paymentState.plan_expires_at' => 'nullable|date|after:today',
        ]);
        
        $planExpiresAt = null;
        if ($this->paymentState['plan_expires_at']) {
            $planExpiresAt = Carbon::parse($this->paymentState['plan_expires_at']);
        }
        
        // Set expiry based on plan type if not manually set
        if (!$planExpiresAt && in_array($this->paymentState['payment_plan'], ['monthly', 'yearly'])) {
            if ($this->paymentState['payment_plan'] === 'monthly') {
                $planExpiresAt = Carbon::now()->addMonth();
            } elseif ($this->paymentState['payment_plan'] === 'yearly') {
                $planExpiresAt = Carbon::now()->addYear();
            }
        }
        
        $this->selectedUser->update([
            'payment_plan' => $this->paymentState['payment_plan'],
            'payment_enabled' => $this->paymentState['payment_enabled'],
            'plan_expires_at' => $planExpiresAt,
            'free_listings_used' => 0, // Reset free listings counter
            'free_listings_reset_at' => now()->addMonth(),
        ]);
        
        $this->resetModals();
        $this->dispatch('notify', type: 'success', message: 'Podešavanja plaćanja su uspešno ažurirana!');
    }
    
    public function grantMonthlyPlan($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'payment_plan' => 'monthly',
            'plan_expires_at' => Carbon::now()->addMonth(),
        ]);
        
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'admin_grant',
            'amount' => 0,
            'status' => 'completed',
            'description' => 'Admin odobrio mesečni plan',
        ]);
        
        $this->dispatch('notify', type: 'success', message: 'Mesečni plan je odobren korisniku!');
    }
    
    public function grantYearlyPlan($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'payment_plan' => 'yearly',
            'plan_expires_at' => Carbon::now()->addYear(),
        ]);
        
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'admin_grant',
            'amount' => 0,
            'status' => 'completed',
            'description' => 'Admin odobrio godišnji plan',
        ]);
        
        $this->dispatch('notify', type: 'success', message: 'Godišnji plan je odobren korisniku!');
    }
    
    public function disablePaymentForUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'payment_enabled' => false,
            'payment_plan' => 'free',
            'plan_expires_at' => null,
        ]);
        
        $this->dispatch('notify', type: 'success', message: 'Plaćanje je isključeno za korisnika!');
    }
    
    public function enablePaymentForUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'payment_enabled' => true,
            'payment_plan' => 'per_listing',
        ]);
        
        $this->dispatch('notify', type: 'success', message: 'Plaćanje je uključeno za korisnika!');
    }

    // User Verification Methods
    public function openVerificationModal($userId)
    {
        $this->resetModals();
        $this->selectedUser = User::findOrFail($userId);
        $this->verificationComment = '';
        $this->showVerificationModal = true;
    }

    public function verifyUser($action)
    {
        $this->validate([
            'verificationComment' => 'nullable|string|max:500'
        ]);

        if ($action === 'approve') {
            $this->selectedUser->approveVerification(auth()->id(), $this->verificationComment);
            $message = 'Korisnik je uspešno verifikovan!';
        } elseif ($action === 'reject') {
            $this->selectedUser->rejectVerification(auth()->id(), $this->verificationComment);
            $message = 'Verifikacija je odbijena!';
        } else {
            session()->flash('error', 'Nevaljan action za verifikaciju.');
            return;
        }

        // Send notification to user
        \App\Models\Message::create([
            'sender_id' => 1, // System
            'receiver_id' => $this->selectedUser->id,
            'listing_id' => null,
            'message' => $action === 'approve' 
                ? "Vaš nalog je uspešno verifikovan! Sada imate status verifikovanog korisnika." . ($this->verificationComment ? "\n\nNapomena: {$this->verificationComment}" : '')
                : "Vaša verifikacija je odbijena." . ($this->verificationComment ? "\n\nRazlog: {$this->verificationComment}" : ''),
            'subject' => $action === 'approve' ? 'Nalog verifikovan' : 'Verifikacija odbijena',
            'is_system_message' => true,
            'is_read' => false
        ]);

        $this->showVerificationModal = false;
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function resetVerification($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'verification_status' => 'unverified',
            'verification_comment' => null,
            'verified_at' => null,
            'verified_by' => null
        ]);

        $this->dispatch('notify', type: 'success', message: 'Status verifikacije je resetovan!');
    }

}