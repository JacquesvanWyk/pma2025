# Pest Testing Conventions

## File Structure

```
tests/
├── Feature/           # Integration tests (HTTP, database)
│   ├── Auth/         # Auth feature tests
│   ├── Settings/     # Settings feature tests
│   └── DashboardTest.php
├── Unit/             # Isolated unit tests
├── Pest.php          # Global config
└── TestCase.php      # Base test case
```

## Test Format

```php
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});
```

## Rules

- Test names: descriptive sentences (not method names)
- Use `test()` function, not `it()`
- Use route names: `route('dashboard')` not `/dashboard`
- Use factories for test data
- Use `actingAs($user)` for authentication
- Chain assertions: `$response->assertOk()->assertSee('text')`
- Feature tests use `RefreshDatabase` trait (configured in Pest.php)
