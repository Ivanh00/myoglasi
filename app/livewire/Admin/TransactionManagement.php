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
    public $processedTransactions = [];

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

        $oldStatus = $this->selectedTransaction->status;
        $newStatus = $validated['updateState']['status'];
        
        // Update transaction
        $this->selectedTransaction->update([
            'status' => $newStatus,
            'description' => $validated['updateState']['description'],
            'completed_at' => $newStatus === 'completed' ? now() : null
        ]);
        
        // Add credit to user's balance if changing from pending/failed to completed
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            if (in_array($this->selectedTransaction->type, ['credit_topup', 'admin_credit']) && $this->selectedTransaction->amount > 0) {
                $this->selectedTransaction->user->increment('balance', $this->selectedTransaction->amount);
                
                $this->dispatch('notify', type: 'success', message: 
                    'Transakcija ažurirana! Kredit od ' . number_format($this->selectedTransaction->amount, 0, ',', '.') . 
                    ' RSD je dodatan korisniku ' . $this->selectedTransaction->user->name . '.');
            } else {
                $this->dispatch('notify', type: 'success', message: 'Transakcija uspešno ažurirana!');
            }
        } else {
            $this->dispatch('notify', type: 'success', message: 'Transakcija uspešno ažurirana!');
        }
        
        $this->showUpdateModal = false;
        
        // Track this transaction as processed if status changed to completed
        if ($newStatus === 'completed') {
            $this->processedTransactions[] = $this->selectedTransaction->id;
            
            // Broadcast event to refresh user balance components
            $this->dispatch('transactionUpdated', $this->selectedTransaction->user_id);
        }
    }

    public function markAsCompleted($transactionId)
    {
        $transaction = Transaction::with('user')->find($transactionId);
        
        // Don't process if already completed or being processed
        if ($transaction->status === 'completed') {
            $this->dispatch('notify', type: 'info', message: 'Transakcija je već završena!');
            return;
        }
        
        // Check if transaction was already processed in this session
        if (in_array($transactionId, $this->processedTransactions)) {
            $this->dispatch('notify', type: 'info', message: 'Transakcija je već obrađena u ovoj sesiji!');
            return;
        }
        
        // Update transaction status with database lock to prevent race conditions
        $updated = Transaction::where('id', $transactionId)
            ->where('status', '!=', 'completed') // Double check in database
            ->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
            
        if (!$updated) {
            $this->dispatch('notify', type: 'info', message: 'Transakcija je već završena!');
            return;
        }
        
        // Add credit to user's balance if it's a credit topup
        if (in_array($transaction->type, ['credit_topup', 'admin_credit']) && $transaction->amount > 0) {
            $transaction->user->increment('balance', $transaction->amount);
            
            $this->dispatch('notify', type: 'success', message: 
                'Transakcija završena! Kredit od ' . number_format($transaction->amount, 0, ',', '.') . 
                ' RSD je dodatan korisniku ' . $transaction->user->name . '.');
        } else {
            $this->dispatch('notify', type: 'success', message: 'Transakcija označena kao završena!');
        }
        
        // Track this transaction as processed to update UI
        $this->processedTransactions[] = $transactionId;
        
        // Broadcast event to refresh user balance components
        $this->dispatch('transactionUpdated', $transaction->user_id);
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