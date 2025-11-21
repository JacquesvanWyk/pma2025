@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-6">
    <h1 class="pma-heading text-3xl mb-2" style="color: var(--color-indigo);">{{ $title }}</h1>
    <p class="pma-body text-sm" style="color: var(--color-olive);">{{ $description }}</p>
</div>
