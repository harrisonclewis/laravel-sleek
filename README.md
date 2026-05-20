<p align="center">
    <img alt="Laravel Sleek" src="banner.png" width="100%">
</p>

# Introduction

Cleaner component syntax for Laravel Blade.

**With Laravel Sleek**
```blade
<Layouts.App>
    <Form method="POST" action="{{ route('login') }}">
        <Form.Group>
            <Form.Label>Email</Form.Label>
            <Form.Input type="email" name="email" />
        </Form.Group>

        <Button type="submit">Sign in</Button>
    </Form>
</Layouts.App>
```

**Without Laravel Sleek**
```blade
<x-layouts.app>
    <x-form method="POST" action="{{ route('login') }}">
        <x-form.group>
            <x-form.label>Email</x-form.label>
            <x-form.input type="email" name="email" />
        </x-form.group>

        <x-button type="submit">Sign in</x-button>
    </x-form>
</x-layouts.app>
```

## Installation

```bash
composer require harrisonclewis/laravel-sleek
```

## Usage

PascalCase tags are transformed to Laravel's `x-component` syntax at compile time.

```blade
<x-button />                  →  <Button />
<x-user-profile />            →  <UserProfile />
<x-form.input />              →  <Form.Input />
<x-card.body class="p-4">     →  <Card.Body class="p-4">
```

## Configuration

Publish the config file if you need to adjust defaults:

```bash
php artisan vendor:publish --tag=sleek-config
```

```php
// config/sleek.php
return [
    'enabled'     => true,
    'ignore_tags' => [ /* additional tags to leave untouched */ ],
];
```

## Requirements

- PHP ^8.1
- Laravel ^10.0|^11.0|^12.0|^13.0

## License

MIT
