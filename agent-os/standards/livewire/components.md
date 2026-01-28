# Livewire Component Patterns

## Structure

```php
namespace App\Livewire\{Feature};

use Livewire\Attributes\Computed;
use Livewire\Component;

class ComponentName extends Component
{
    // Public properties for state
    public string $name = '';

    // mount() for initialization
    public function mount(): void
    {
        $this->name = Auth::user()->name;
    }

    // Action methods (verb + noun)
    public function updateProfile(): void
    {
        // validate, process, dispatch event
        $this->dispatch('profile-updated', name: $user->name);
    }

    // Computed properties with attribute
    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return /* ... */;
    }
}
```

## Rules

- Organize by feature: `App\Livewire\Settings\`, `App\Livewire\Auth\`
- Use typed public properties for state
- Extract validation rules to `App\Concerns\` traits
- Use `#[Computed]` attribute for derived state
- Dispatch events for cross-component communication
- Action method names: verb + noun (`updateProfile`, `deleteUser`)
