<li class="nav-item dropdown" style="margin: 0; padding: 0;" title="Notificaciones">
    <!-- Bell Button -->
    <button id="notification-bell" class="nav-link icon position-relative p-0 bg-transparent border-0"
        style="outline: none; cursor: pointer;" type="button">
        @if ($unreadCount > 0)
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                <path
                    d="M13.916 2.314A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.74 7.327A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673 9 9 0 0 1-.585-.665" />
                <circle cx="18" cy="8" r="3" />
            </svg>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                <path
                    d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
            </svg>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div id="notification-dropdown" class="dropdown-menu dropdown-menu-end shadow"
        style="display: none; width: 320px; max-height: 400px; overflow-y: auto; font-size: 0.875rem; position: absolute; z-index: 1000; left: -18rem; top: 100%;"
        data-livewire-component="{{ $this->id }}">
        @if($notifications->isEmpty())
            <span class="dropdown-item text-muted text-center py-3">
                No hay notificaciones
            </span>
        @else
            @foreach($notifications as $notification)
                <a class="dropdown-item {{ $notification->read ? '' : 'bg-light' }}" href="{{ $notification->link ?: '#' }}"
                    wire:click.prevent="markAsRead({{ $notification->id }})"
                    style="text-decoration: none; color: inherit; display: block;">
                    <div class="d-flex justify-content-between align-items-start">
                        <strong class="me-2">{{ $notification->title }}</strong>
                        @if(!$notification->read)
                            <span class="badge bg-primary">Nueva</span>
                        @endif
                    </div>
                    <p class="mb-1 text-muted small" style="white-space: normal;">{{ $notification->description }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </a>
                <hr class="dropdown-divider my-0">
            @endforeach

            <!-- Pagination Links -->
            <div class="px-3 py-2 border-top">
                {{ $notifications->links('pagination::bootstrap-4') }}
            </div>

            <div class="px-3 py-2">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100" wire:click="markAllAsRead">
                    Marcar todas como leídas
                </button>
            </div>
        @endif
    </div>
</li>