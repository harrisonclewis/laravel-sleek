<?php

use Illuminate\View\Compilers\BladeCompiler;

it('registers the service provider', function () {
    expect(app()->providerIsLoaded(\HarlewCom\LaravelSleek\SleekServiceProvider::class))->toBeTrue();
});

it('merges the default config', function () {
    expect(config('sleek.enabled'))->toBeTrue();
    expect(config('sleek.ignore_tags'))->toBeArray()->not->toBeEmpty();
});

it('transforms PascalCase tags through the Blade precompiler', function () {
    $blade = app(BladeCompiler::class);

    // Transformation succeeded if Blade throws about 'button' (transformed),
    // not 'Button' (untransformed).
    expect(fn () => $blade->compileString('<Button />'))
        ->toThrow(\InvalidArgumentException::class, 'button');
});

it('does not transform when disabled via config', function () {
    config(['sleek.enabled' => false]);

    $blade = app(BladeCompiler::class);
    $output = $blade->compileString('<Button />');

    expect($output)->not->toContain('x-button');

    config(['sleek.enabled' => true]);
});

it('does not transform standard HTML tags listed in ignore_tags', function () {
    $blade = app(BladeCompiler::class);

    // 'div' is in the default ignore_tags list; it must never become x-div
    $output = $blade->compileString('<div class="wrapper"></div>');
    expect($output)->toContain('<div class="wrapper">');
    expect($output)->not->toContain('x-div');
});
