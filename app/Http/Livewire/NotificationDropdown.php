<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination; // 👈 Add this
use App\Models\Notification;

class NotificationDropdown extends Component
{
    use WithPagination; // 👈 Enable pagination

    public $perPage = 5; // Show 5 per page in dropdown
    public $isOpen = false;

    protected $paginationTheme = 'bootstrap'; // Use Bootstrap styling

    public function mount()
    {
        $this->loadUnreadCount();
    }

    public function loadUnreadCount()
    {
        $this->unreadCount = Notification::where('employee_id', auth()->id())
            ->where('read', false)
            ->count();
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadUnreadCount(); // Refresh count when opening
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('employee_id', auth()->id())
            ->findOrFail($notificationId);

        if (!$notification->read) {
            $notification->update(['read' => true]);
            $this->loadUnreadCount(); // Update badge count
        }
    }

    public function markAllAsRead()
    {
        Notification::where('employee_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        $this->loadUnreadCount();
        $this->resetPage(); // Reset to first page after marking all
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        $notifications = Notification::where('employee_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.notification-dropdown', [
            'notifications' => $notifications,
        ]);
    }
}