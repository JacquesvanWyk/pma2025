# Livewire Blade View Patterns

## Flux UI Components

Use Flux UI components, not raw HTML:

```blade
{{-- Inputs --}}
<flux:input wire:model="name" :label="__('Name')" type="text" required />
<flux:input wire:model="email" :label="__('Email')" type="email" required />

{{-- Buttons --}}
<flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>

{{-- Text/Links --}}
<flux:text class="mt-4">{{ __('Message here') }}</flux:text>
<flux:link wire:click.prevent="doSomething">{{ __('Click here') }}</flux:link>

{{-- Headings --}}
<flux:heading class="sr-only">{{ __('Section Title') }}</flux:heading>
```

## Wire Directives

```blade
wire:model="property"           {{-- Deferred binding --}}
wire:model.live="property"      {{-- Real-time binding --}}
wire:submit="methodName"        {{-- Form submission --}}
wire:click="methodName"         {{-- Click handler --}}
wire:click.prevent="method"     {{-- Prevent default --}}
```

## Rules

- Always use `__()` for translatable strings
- Use `$this->computedProperty` in Blade for computed props
- Nest Livewire components: `<livewire:settings.delete-user-form />`
- Use partials: `@include('partials.settings-heading')`
- Use custom components: `<x-settings.layout>`
