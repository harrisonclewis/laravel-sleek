<?php

use HarlewCom\LaravelSleek\SleekComponentCompiler;

// Self-closing tags

it('transforms a simple self-closing PascalCase tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button />'))->toBe('<x-button />');
});

it('transforms a self-closing tag without a space before the slash', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button/>'))->toBe('<x-button/>');
});

it('transforms a self-closing tag with attributes', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button class="primary" type="submit" />'))->toBe('<x-button class="primary" type="submit" />');
});

// Opening and closing tags

it('transforms an opening PascalCase tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button>'))->toBe('<x-button>');
});

it('transforms a closing PascalCase tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('</Button>'))->toBe('</x-button>');
});

it('transforms a matched opening and closing tag pair', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button>Click me</Button>'))->toBe('<x-button>Click me</x-button>');
});

it('transforms an opening tag with attributes', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Button class="primary">'))->toBe('<x-button class="primary">');
});

// PascalCase to kebab-case

it('converts multi-word PascalCase to kebab-case', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<MyButton />'))->toBe('<x-my-button />');
});

it('converts deeply nested PascalCase to kebab-case', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<PrimaryActionButton />'))->toBe('<x-primary-action-button />');
});

// Dot notation

it('transforms a dot-notation self-closing tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Form.Input />'))->toBe('<x-form.input />');
});

it('transforms a dot-notation opening tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Form.Input>'))->toBe('<x-form.input>');
});

it('transforms a dot-notation closing tag', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('</Form.Input>'))->toBe('</x-form.input>');
});

it('transforms deeply nested dot notation', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<Form.Group.Item />'))->toBe('<x-form.group.item />');
});

it('converts PascalCase segments in dot notation to kebab-case', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<MyForm.TextInput />'))->toBe('<x-my-form.text-input />');
});

// Multiple components

it('transforms multiple components in one string', function () {
    $compiler = new SleekComponentCompiler();
    $input = '<Card><Card.Header /><Card.Body /></Card>';
    $expected = '<x-card><x-card.header /><x-card.body /></x-card>';
    expect($compiler->compile($input))->toBe($expected);
});

// HTML tag preservation

it('does not transform lowercase HTML tags', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('<div class="foo">'))->toBe('<div class="foo">');
});

it('does not transform ignored HTML tags listed in config', function () {
    $ignoreTags = ['div', 'span', 'p'];
    $compiler = new SleekComponentCompiler($ignoreTags);
    expect($compiler->compile('<div>'))->toBe('<div>');
});

it('transforms components while leaving surrounding HTML intact', function () {
    $compiler = new SleekComponentCompiler(['div']);
    $input = '<div><Button /></div>';
    $expected = '<div><x-button /></div>';
    expect($compiler->compile($input))->toBe($expected);
});

// Dynamic bindings and Blade expressions

it('preserves blade directives alongside component tags', function () {
    $compiler = new SleekComponentCompiler();
    $input = '<Button :label="$text" />';
    expect($compiler->compile($input))->toBe('<x-button :label="$text" />');
});

it('preserves wire:model and other framework attributes', function () {
    $compiler = new SleekComponentCompiler();
    $input = '<TextInput wire:model="email" />';
    expect($compiler->compile($input))->toBe('<x-text-input wire:model="email" />');
});

// Edge cases

it('returns an empty string unchanged', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile(''))->toBe('');
});

it('returns plain text unchanged', function () {
    $compiler = new SleekComponentCompiler();
    expect($compiler->compile('Hello world'))->toBe('Hello world');
});
