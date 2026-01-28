# Filament Panel Setup

## AdminPanelProvider Pattern

```php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets');
    }
}
```

## File Structure

```
app/Filament/
├── Resources/      # CRUD resources (UserResource, etc.)
├── Pages/          # Custom pages
└── Widgets/        # Dashboard widgets
```

## Rules

- Use `discoverResources/Pages/Widgets` for auto-registration
- Use `Color` enum for theme colors, not hex strings
- Panel path matches panel id: `->id('admin')->path('admin')`
- Enable login on admin panels: `->login()`
