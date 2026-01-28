# Concerns (Reusable Traits)

## Location

`App\Concerns\` for shared traits used across multiple classes.

## Pattern

```php
namespace App\Concerns;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }

    protected function currentPasswordRules(): array
    {
        return ['required', 'string', 'current_password'];
    }
}
```

## Usage

```php
use App\Concerns\PasswordValidationRules;

class Password extends Component
{
    use PasswordValidationRules;

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ]);
    }
}
```

## Rules

- Name traits by what they provide: `PasswordValidationRules`, `ProfileValidationRules`
- Keep traits focused on one concern
- Use for validation rules, common queries, shared behavior
- Methods should be `protected` unless needed externally
