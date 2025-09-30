<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListingReport;
use App\Models\ServiceReport;
use App\Models\Message;
use App\Models\Listing;
use App\Models\Service;

class ReportManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, pending, reviewed, resolved
    public $perPage = 20;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $selectedReport = null;
    public $showViewModal = false;
    public $showActionModal = false;
    public $showDetailsModal = false;
    public $showDeleteModal = false;
    public $showRestoreModal = false;
    public $adminAction = ''; // mark_reviewed, mark_resolved, delete_listing
    public $adminNotes = '';
    public $notifyUser = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'page' => ['except' => 1]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
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

    public function viewReport($reportId, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->showViewModal = true;
    }

    public function viewDetails($reportId, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->showDetailsModal = true;
    }

    public function openActionModal($reportId, $action, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->adminAction = $action;
        $this->adminNotes = '';
        $this->showActionModal = true;
    }

    public function executeAction()
    {
        if (!$this->selectedReport) return;

        try {
            switch ($this->adminAction) {
                case 'mark_reviewed':
                    $this->selectedReport->update([
                        'status' => 'reviewed',
                        'admin_notes' => $this->adminNotes
                    ]);
                    
                    // Send notification to reporting user
                    $this->sendNotificationToReporter('pregledana');
                    $this->dispatch('notify', type: 'success', message: 'Prijava je označena kao pregledana.');
                    break;

                case 'mark_resolved':
                    $this->selectedReport->update([
                        'status' => 'resolved',
                        'admin_notes' => $this->adminNotes
                    ]);
                    
                    // Send notification to reporting user
                    $this->sendNotificationToReporter('rešena');
                    $this->dispatch('notify', type: 'success', message: 'Prijava je označena kao rešena.');
                    break;

                case 'delete_listing':
                    // Mark listing as inactive instead of hard delete
                    $this->selectedReport->listing->update(['status' => 'inactive']);
                    
                    $this->selectedReport->update([
                        'status' => 'resolved',
                        'admin_notes' => 'Oglas je uklonjen zbog kršenja pravila. ' . $this->adminNotes
                    ]);
                    
                    // Send notification to listing owner
                    $this->sendNotificationToListingOwner();
                    
                    // Send notification to reporting user
                    $this->sendNotificationToReporter('rešena - oglas uklonjen');
                    
                    $this->dispatch('notify', type: 'success', message: 'Oglas je uklonjen i prijava je rešena.');
                    break;
            }

            $this->resetModals();

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri izvršavanju akcije: ' . $e->getMessage());
        }
    }

    private function sendNotificationToReporter($status)
    {
        Message::create([
            'sender_id' => auth()->id(), // Admin
            'receiver_id' => $this->selectedReport->user_id,
            'listing_id' => $this->selectedReport->listing_id,
            'message' => "Vaša prijava oglasa '{$this->selectedReport->listing->title}' je {$status}." .
                        ($this->adminNotes ? "\n\nNapomena administratora:\n{$this->adminNotes}" : ''),
            'subject' => 'Ažuriranje prijave oglasa',
            'is_system_message' => true,
            'is_read' => false
        ]);
    }

    private function sendNotificationToListingOwner()
    {
        Message::create([
            'sender_id' => auth()->id(), // Admin
            'receiver_id' => $this->selectedReport->listing->user_id,
            'listing_id' => $this->selectedReport->listing_id,
            'message' => "Vaš oglas '{$this->selectedReport->listing->title}' je uklonjen zbog kršenja pravila platforme." .
                        ($this->adminNotes ? "\n\nRazlog:\n{$this->adminNotes}" : ''),
            'subject' => 'Oglas uklonjen',
            'is_system_message' => true,
            'is_read' => false
        ]);
    }

    public function viewReportDetails($reportId, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->showDetailsModal = true;
    }

    public function markAsReviewed($reportId, $type = 'listing')
    {
        try {
            if ($type === 'service') {
                $report = ServiceReport::with(['user', 'service'])->find($reportId);
                $itemType = 'usluge';
                $itemTitle = $report->service->title ?? 'N/A';
                $itemId = $report->service_id;
            } else {
                $report = ListingReport::with(['user', 'listing'])->find($reportId);
                $itemType = 'oglasa';
                $itemTitle = $report->listing->title ?? 'N/A';
                $itemId = $report->listing_id;
            }

            if (!$report) {
                $this->dispatch('notify', type: 'error', message: 'Prijava nije pronađena.');
                return;
            }

            $report->update([
                'status' => 'reviewed',
                'admin_notes' => 'Pregledano od strane administratora ' . auth()->user()->name
            ]);

            // Send notification to reporting user
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $report->user_id,
                'listing_id' => $type === 'listing' ? $itemId : null,
                'service_id' => $type === 'service' ? $itemId : null,
                'message' => "Vaša prijava {$itemType} '{$itemTitle}' je pregledana od strane administratora.",
                'subject' => 'Prijava pregledana',
                'is_system_message' => true,
                'is_read' => false
            ]);

            $this->dispatch('notify', type: 'success', message: 'Prijava je označena kao pregledana.');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška: ' . $e->getMessage());
        }
    }

    public function markAsResolved($reportId, $type = 'listing')
    {
        try {
            if ($type === 'service') {
                $report = ServiceReport::with(['user', 'service'])->find($reportId);
                $itemType = 'usluge';
                $itemTitle = $report->service->title ?? 'N/A';
                $itemId = $report->service_id;
            } else {
                $report = ListingReport::with(['user', 'listing'])->find($reportId);
                $itemType = 'oglasa';
                $itemTitle = $report->listing->title ?? 'N/A';
                $itemId = $report->listing_id;
            }

            if (!$report) {
                $this->dispatch('notify', type: 'error', message: 'Prijava nije pronađena.');
                return;
            }

            $report->update([
                'status' => 'resolved',
                'admin_notes' => 'Rešeno od strane administratora ' . auth()->user()->name
            ]);

            // Send notification to reporting user
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $report->user_id,
                'listing_id' => $type === 'listing' ? $itemId : null,
                'service_id' => $type === 'service' ? $itemId : null,
                'message' => "Vaša prijava {$itemType} '{$itemTitle}' je rešena.",
                'subject' => 'Prijava rešena',
                'is_system_message' => true,
                'is_read' => false
            ]);

            $this->dispatch('notify', type: 'success', message: 'Prijava je označena kao rešena.');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška: ' . $e->getMessage());
        }
    }

    public function confirmDeleteListing($reportId, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->showDeleteModal = true;
        $this->notifyUser = true;
        $this->adminNotes = '';
    }

    public function deleteListing()
    {
        if (!$this->selectedReport) return;

        try {
            $listing = $this->selectedReport->listing;
            
            if ($listing->status === 'inactive') {
                // Hard delete if already soft deleted
                $listing->delete();
                
                $this->selectedReport->update([
                    'status' => 'resolved',
                    'admin_notes' => 'Oglas je trajno obrisan. ' . $this->adminNotes
                ]);
                
                $this->dispatch('notify', type: 'success', message: 'Oglas je trajno obrisan.');
            } else {
                // Soft delete - mark as inactive
                $listing->update(['status' => 'inactive']);
                
                $this->selectedReport->update([
                    'status' => 'resolved',
                    'admin_notes' => 'Oglas je uklonjen zbog kršenja pravila. ' . $this->adminNotes
                ]);
                
                if ($this->notifyUser) {
                    // Send notification to listing owner
                    $this->sendNotificationToListingOwner();
                }
                
                // Send notification to reporting user
                $this->sendNotificationToReporter('rešena - oglas uklonjen');
                
                $this->dispatch('notify', type: 'success', message: 'Oglas je uklonjen i može biti vraćen ako je potrebno.');
            }
            
            $this->resetModals();

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri brisanju oglasa: ' . $e->getMessage());
        }
    }

    public function restoreListing($reportId, $type = 'listing')
    {
        try {
            if ($type === 'service') {
                $report = ServiceReport::with(['user', 'service'])->find($reportId);
                $item = $report->service ?? null;
                $itemType = 'Usluga';
                $itemId = $report->service_id;
            } else {
                $report = ListingReport::with(['user', 'listing'])->find($reportId);
                $item = $report->listing ?? null;
                $itemType = 'Oglas';
                $itemId = $report->listing_id;
            }

            if (!$report || !$item) {
                $this->dispatch('notify', type: 'error', message: $itemType . ' nije pronađen.');
                return;
            }

            $item->update(['status' => 'active']);

            $report->update([
                'admin_notes' => $report->admin_notes . ' | ' . $itemType . ' vraćen ' . now()->format('d.m.Y H:i')
            ]);

            // Send notification to item owner
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $item->user_id,
                'listing_id' => $type === 'listing' ? $itemId : null,
                'service_id' => $type === 'service' ? $itemId : null,
                'message' => "Vaš " . strtolower($itemType) . " '{$item->title}' je vraćen na platformu.",
                'subject' => $itemType . ' vraćen',
                'is_system_message' => true,
                'is_read' => false
            ]);

            $this->dispatch('notify', type: 'success', message: $itemType . ' je vraćen na platformu.');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Greška pri vraćanju: ' . $e->getMessage());
        }
    }

    public function saveAdminNotes()
    {
        if (!$this->selectedReport) return;

        $this->selectedReport->update([
            'admin_notes' => $this->adminNotes
        ]);

        $this->dispatch('notify', type: 'success', message: 'Napomene su sačuvane.');
    }

    public function openDeleteModal($reportId, $type = 'listing')
    {
        if ($type === 'service') {
            $this->selectedReport = ServiceReport::with(['user', 'service'])->find($reportId);
            $this->selectedReport->report_type = 'service';
        } else {
            $this->selectedReport = ListingReport::with(['user', 'listing'])->find($reportId);
            $this->selectedReport->report_type = 'listing';
        }
        $this->showDeleteModal = true;
        $this->notifyUser = true;
        $this->adminNotes = '';
    }

    public function resetModals()
    {
        $this->showViewModal = false;
        $this->showActionModal = false;
        $this->showDetailsModal = false;
        $this->showDeleteModal = false;
        $this->selectedReport = null;
        $this->adminAction = '';
        $this->adminNotes = '';
        $this->notifyUser = true;
    }

    public function render()
    {
        // Get listing reports
        $listingQuery = ListingReport::with(['user', 'listing']);

        // Get service reports
        $serviceQuery = ServiceReport::with(['user', 'service']);

        // Search filter for listings
        if ($this->search) {
            $listingQuery->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('listing', function($listingQuery) {
                    $listingQuery->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reason', 'like', '%' . $this->search . '%')
                ->orWhere('details', 'like', '%' . $this->search . '%');
            });

            // Search filter for services
            $serviceQuery->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('service', function($serviceQuery) {
                    $serviceQuery->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reason', 'like', '%' . $this->search . '%')
                ->orWhere('details', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $listingQuery->where('status', $this->statusFilter);
            $serviceQuery->where('status', $this->statusFilter);
        }

        // Get both types of reports
        $listingReports = $listingQuery->get()->map(function($report) {
            $report->report_type = 'listing';
            return $report;
        });

        $serviceReports = $serviceQuery->get()->map(function($report) {
            $report->report_type = 'service';
            return $report;
        });

        // Concat (not merge - merge uses keys and IDs can overlap!)
        $allReports = $listingReports->concat($serviceReports);

        // Sort by the selected field
        if ($this->sortDirection === 'desc') {
            $allReports = $allReports->sortByDesc($this->sortField);
        } else {
            $allReports = $allReports->sortBy($this->sortField);
        }

        // Manual pagination
        $currentPage = request()->get('page', 1);
        $reports = new \Illuminate\Pagination\LengthAwarePaginator(
            $allReports->forPage($currentPage, $this->perPage),
            $allReports->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get statistics
        $stats = [
            'total' => ListingReport::count() + ServiceReport::count(),
            'pending' => ListingReport::where('status', 'pending')->count() + ServiceReport::where('status', 'pending')->count(),
            'reviewed' => ListingReport::where('status', 'reviewed')->count() + ServiceReport::where('status', 'reviewed')->count(),
            'resolved' => ListingReport::where('status', 'resolved')->count() + ServiceReport::where('status', 'resolved')->count(),
            'listing_total' => ListingReport::count(),
            'service_total' => ServiceReport::count()
        ];

        return view('livewire.admin.report-management', compact('reports', 'stats'))
            ->layout('layouts.admin');
    }
}