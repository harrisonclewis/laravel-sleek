# Laravel Sleek

React-style component syntax for Laravel Blade templates.

## Overview

Laravel Sleek transforms PascalCase component tags into Laravel's native x-component syntax, allowing you to write Blade components with a React-like syntax.

```blade
<!-- Write this -->
<Button type="primary" />
<Form.Input name="email" />

<!-- Get this -->
<x-button type="primary" />
<x-form.input name="email" />
```

## Installation

Install via Composer:

```bash
composer require harlew-com/laravel-sleek
```

The package will automatically register itself via Laravel's package discovery.

### Configuration (Optional)

Publish the configuration file:

```bash
php artisan vendor:publish --tag=sleek-config
```

This creates `config/sleek.php` where you can:

- Enable/disable the compiler
- Add custom HTML tags to ignore

```php
return [
    'enabled' => true,

    'ignore_tags' => [
        // Standard HTML tags are already included
        // Add custom tags here if needed
    ],
];
```

## Usage

### Basic Components

Write PascalCase component names that automatically transform to kebab-case:

```blade
<Button />              → <x-button />
<UserProfile />         → <x-user-profile />
<NavigationMenu />      → <x-navigation-menu />
```

### Nested Components (Dot Notation)

Use dot notation for namespaced components:

```blade
<Form.Input />          → <x-form.input />
<Layout.Header />       → <x-layout.header />
<Card.Title />          → <x-card.title />
```

**Alternative: Subfolder Components Without Dot Notation**

If you prefer subfolders without dot notation (e.g., `<FormInput />` instead of `<Form.Input />`), register component aliases in your `AppServiceProvider`:

```php
use Illuminate\Support\Facades\Blade;

public function boot()
{
    Blade::component('components.forms.input', 'FormInput');
}
```

Then use:

```blade
<FormInput />           → Uses components/forms/input.blade.php
```

### With Attributes

All attributes are preserved:

```blade
<Button type="submit" class="btn-primary" @click="submit">
    Submit Form
</Button>

<!-- Transforms to -->
<x-button type="submit" class="btn-primary" @click="submit">
    Submit Form
</x-button>
```

### Self-Closing Tags

Both syntaxes work:

```blade
<Avatar src="/user.jpg" />
<Avatar src="/user.jpg"/>
```

## Requirements

- PHP 8.1 or higher
- Laravel >=10.0

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Harrison Lewis](https://github.com/harrisonclewis)

## Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/harrisonclewis/laravel-sleek).
