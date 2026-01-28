# Project Structure

## App Directory

```
app/
├── Actions/          # Single-purpose action classes (Fortify)
├── Concerns/         # Reusable traits
├── Filament/         # Filament resources, pages, widgets
│   ├── Resources/
│   ├── Pages/
│   └── Widgets/
├── Http/
│   └── Controllers/  # Thin controllers (prefer Livewire)
├── Livewire/         # Livewire components by feature
│   ├── Actions/      # Reusable Livewire actions
│   └── Settings/     # Settings-related components
├── Models/           # Eloquent models
└── Providers/        # Service providers
    └── Filament/     # Filament panel providers
```

## Resources Directory

```
resources/views/
├── components/       # Blade components (x-*)
├── layouts/          # Layout templates
├── livewire/         # Livewire component views
│   ├── auth/
│   └── settings/
└── partials/         # Reusable partials (@include)
```

## Test Directory

```
tests/
├── Feature/          # Integration tests (mirrors app structure)
│   ├── Auth/
│   └── Settings/
├── Unit/             # Isolated unit tests
├── Pest.php          # Pest configuration
└── TestCase.php      # Base test case
```

## Rules

- Organize by feature, not by type
- Livewire components: `App\Livewire\{Feature}\ComponentName`
- Views match components: `resources/views/livewire/{feature}/component-name.blade.php`
- Tests mirror app structure
