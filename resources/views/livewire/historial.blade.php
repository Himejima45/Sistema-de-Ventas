@php
    $statuses = [
        'PENDING' => 'Pendiente',
        'PAID' => 'Pagado',
        'CANCELED' => 'Cancelado',
    ];
@endphp

<div class="col sales layout-top-spacing gap-4">
    <x-home_button />

    <div class="widget-content">
        <div class="col mx-auto">
            @foreach ($carts as $cart)
                @if ($cart->products()->count() > 0)
                    <h6 class="mt-2">Solicitud - {{ $cart->created_at->format('d-m-Y h:i a') }}
                    </h6>
                    <div class="d-flex items-justify-center align-items-center">
                        <p class="font-weight-bold mb-0 mr-2">
                            {{ $statuses[$cart->status] }}
                        </p>
                        @if ($cart->status === 'PENDING')
                            <button wire:click="delete({{ $cart->id }})" class="btn btn-danger"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-eraser">
                                    <path
                                        d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                    <path d="M22 21H7" />
                                    <path d="m5 11 9 9" />
                                </svg></button>
                        @endif
                    </div>
                    <div class="row">
                        @foreach ($cart->products as $details)
                            <div class="card m-2" style="width: 14rem;">
                                <img class="card-img-top" height="140" src="{{ $details->product->getImage() }}"
                                    alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $details->product->name }}</h5>
                                    <small class="-mt-4">{{ $details->product->price }}$</small>
                                    <p class="card-text">
                                        <span class="badge badge-secondary">{{ $details->product->category->name }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
            {{ $carts->links() }}
        </div>
    </div>

</div>