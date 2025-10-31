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
                    <h6 class="mt-2">
                        Solicitud - {{ $cart->created_at->format('d-m-Y h:i a') }}
                    </h6>
                    <div class="d-flex flex-column items-justify-center" style="gap: 1rem">
                        <p class="font-weight-bold mb-0 mr-2">
                            {{ $statuses[$cart->status] }} (<span>#{{ $cart->code }}</span>)
                        </p>
                        @if ($cart->status === 'PENDING')
                            <div class="d-flex items-justify-center" style="gap: 1rem">
                                <button wire:click="delete({{ $cart->id }})" class="btn btn-danger" title="Borrar carrito">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                        <path d="M22 21H7" />
                                        <path d="m5 11 9 9" />
                                    </svg>
                                </button>
                                <a class="btn btn-info" title="Contactar por whatsapp"
                                    href="https://wa.me/58{{ $cart->client->phone }}?text=Hola%2C%20acabo%20de%20registrar%20los%20productos%20que%20deseo%20comprar%2C%20este%20fue%20mi%20pedido%20%23{{ urlencode($cart->code) }}%20%C2%BFMe%20podr%C3%ADa%20indicar%20cuando%20podr%C3%ADa%20retirarlos%20de%20la%20tienda%3F%20Gracias">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"">
                                                                                                                                                                                                                                                                                                                                                                                    <path d="
                                        M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2
                                        2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292
                                        1.233 14 14 0 0 0 6.392 6.384" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row" style="margin-inline: -0.5rem">
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