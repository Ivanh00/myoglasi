<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\User;

class TransactionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedTransaction = null;
    public $showViewModal = false;
    public $showUpdateModal = false;

    public $filters = [
        'type' => '',
        'status' => '',
        'user_id' => '',
        'date_from' => '',
        'date_to' => ''
    ];

    public $statusOptions = [
        'pending' => 'Na čekanju',
        'completed' => 'Završeno',
        'failed' => 'Neuspešno'
    ];

    public $typeOptions = [
        'deposit' => 'Uplata',
        'withdrawal' => 'Isplata',
        'fee' => 'Naknada',
        'refund' => 'Povrat',
        'purchase' => 'Kupovina'
    ];

    public $updateState = [
        'status' => '',
        'description' => ''
    ];

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function mount()
    {
        // Inicijalizacija filtera ako je potrebno
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

    public function applyFilters($query)
    {
        return $query
            ->when($this->filters['type'], function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($this->filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($this->filters['user_id'], function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->when($this->filters['date_from'], function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($this->filters['date_to'], function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });
    }

    public function viewTransaction($transactionId)
    {
        $this->selectedTransaction = Transaction::with('user')->find($transactionId);
        $this->showViewModal = true;
    }

    public function editTransaction($transactionId)
    {
        $this->selectedTransaction = Transaction::with('user')->find($transactionId);
        
        $this->updateState = [
            'status' => $this->selectedTransaction->status,
            'description' => $this->selectedTransaction->description
        ];

        $this->showUpdateModal = true;
    }

    public function updateTransaction()
    {
        $validated = $this->validate([
            'updateState.status' => 'required|in:pending,completed,failed',
            'updateState.description' => 'nullable|string|max:500'
        ]);

        $this->selectedTransaction->update($validated['updateState']);
        
        $this->showUpdateModal = false;
        $this->dispatch('notify', type: 'success', message: 'Transakcija uspešno ažurirana!');
    }

    public function markAsCompleted($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        $transaction->update(['status' => 'completed']);
        
        $this->dispatch('notify', type: 'success', message: 'Transakcija označena kao završena!');
    }

    public function markAsFailed($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        $transaction->update(['status' => 'failed']);
        
        $this->dispatch('notify', type: 'success', message: 'Transakcija označena kao neuspešna!');
    }

    public function resetFilters()
    {
        $this->filters = [
            'type' => '',
            'status' => '',
            'user_id' => '',
            'date_from' => '',
            'date_to' => ''
        ];
    }

    public function exportTransactions()
    {
        // Implementacija exporta u CSV/Excel formatu
        $this->dispatch('notify', type: 'info', message: 'Funkcionalnost izvoza u izradi...');
    }

    public function getStats()
    {
        $total = Transaction::count();
        $completed = Transaction::where('status', 'completed')->count();
        $pending = Transaction::where('status', 'pending')->count();
        $failed = Transaction::where('status', 'failed')->count();
        
        $totalAmount = Transaction::where('status', 'completed')->sum('amount');
        $totalDeposits = Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $totalFees = Transaction::where('type', 'fee')->where('status', 'completed')->sum('amount');

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'failed' => $failed,
            'totalAmount' => $totalAmount,
            'totalDeposits' => $totalDeposits,
            'totalFees' => abs($totalFees) // Uzimamo apsolutnu vrednost za naknade
        ];
    }

    public function render()
    {
        $transactions = Transaction::with('user')
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%')
                      ->orWhere('amount', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filters, function ($query) {
                return $this->applyFilters($query);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = $this->getStats();
        $users = User::orderBy('name')->get();

        return view('livewire.admin.transaction-management', compact('transactions', 'stats', 'users'))
            ->layout('layouts.admin');
    }
}