@props(['id', 'attributes' => []])

<a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#{{ $id ?? 'theModal' }}"
    style="padding: 0.5rem; display: flex; gap: 0.5rem" {{ $attributes }}>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" />
        <path d="M8 12h8" />
        <path d="M12 8v8" />
    </svg>
    AGREGAR
</a>