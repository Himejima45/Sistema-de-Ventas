<div class="cart-icon d-flex" style="cursor: pointer; transition: all 0.3s ease; user-select: none; gap: 0.5rem">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: all 0.3s ease;">
        <circle cx="8" cy="21" r="1" />
        <circle cx="19" cy="21" r="1" />
        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
    </svg>
    @if($total_items > 0)
        <span class="badge badge-sm badge-pill badge-ghost" style="transition: all 0.3s ease;">
            {{ $total_items }}
        </span>
    @endif
</div>