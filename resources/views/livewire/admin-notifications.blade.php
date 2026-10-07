<div>
    <div class="header-notifications-list" style="overflow: hidden;overflow-y: scroll;">
        @forelse ($notifications as $notification)
            <div class="dropdown-item">
                <div class="d-flex align-items-center">
                    <div class="notify bg-light-primary text-primary">
                        @if ($notification->type == 'commande')
                            <i class="ri-shopping-bag-line text-primary-color"></i>
                        @else
                            <i class="bx bx-group text-primary-color"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1" onclick="url('/open_url_notification?url={{ $notification->url }}')">
                        <h6 class="msg-name">
                            {{ Str::limit($notification->titre , 25) }}
                            <span class="msg-time float-end">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </h6>
                        <p class="msg-info">
                            {{ $notification->message }}
                        </p>
                    </div>
                    <div>
                        <i class="ri-close-fill" wire:click="delete( {{ $notification->id }})"></i>
                    </div>
                </div>
            </div>
        @empty
            <a class="dropdown-item d-flex w-100 py-3 text-muted text-center w-100 fw-bold border-bottom border-gray-200">
                Aucune notification en ce moment !
            </a>
        @endforelse

    </div>
    @role('admin')
        @if ($notifications->count() > 0)
            <div class="text-center p-2">
                <a href="{{ route('delete-all-notifications') }}">
                    Supprimer toutes les notifications.
                </a>
            </div>
        @endif
    @endrole
</div>
