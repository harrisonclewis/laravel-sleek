<?php

namespace Harlew\Sleek;

use Illuminate\Support\Str;

class SleekComponentCompiler
{
    /**
     * Custom HTML tags that should not be transformed.
     *
     * @var array
     */
    protected $ignoreTags = [];

    /**
     * Create a new React-style component compiler.
     *
     * @param array $customHtmlTags
     */
    public function __construct(array $ignoreTags = [])
    {
        $this->ignoreTags = $ignoreTags;
    }

    /**
     * Compile PascalCase component tags to Laravel x-component format.
     *
     * @param string $value
     * @return string
     */
    public function compile(string $value): string
    {
        // Handle self-closing tags: <ComponentName ... />
        $value = preg_replace_callback(
            '/<([A-Z][a-zA-Z0-9]*(?:\.[A-Z][a-zA-Z0-9]*)*)((?:\s[^>\/]*)?)\s*(\/>)/',
            function (array $matches) {
                if ($this->isHtmlTag($matches[1])) {
                    return $matches[0];
                }
                $componentName = $this->convertPascalCaseToKebabCase($matches[1]);
                $attributes = rtrim($matches[2] ?? ''); // Remove trailing whitespace

                // Preserve original spacing pattern: if there was a space before />, keep it
                $originalTag = $matches[0];
                $hasSpaceBeforeSlash = preg_match('/\s\/>$/', $originalTag);

                if (empty($attributes)) {
                    return $hasSpaceBeforeSlash ? "<x-{$componentName} />" : "<x-{$componentName}/>";
                } else {
                    return $hasSpaceBeforeSlash ? "<x-{$componentName}{$attributes} />" : "<x-{$componentName}{$attributes}/>";
                }
            },
            $value
        );

        // Handle opening tags: <ComponentName ...>
        $value = preg_replace_callback(
            '/<([A-Z][a-zA-Z0-9]*(?:\.[A-Z][a-zA-Z0-9]*)*)((?:\s[^>]*)?)\s*>/',
            function (array $matches) {
                if ($this->isHtmlTag($matches[1])) {
                    return $matches[0];
                }
                $componentName = $this->convertPascalCaseToKebabCase($matches[1]);
                $attributes = rtrim($matches[2] ?? ''); // Remove trailing whitespace
                return "<x-{$componentName}{$attributes}>";
            },
            $value
        );

        // Handle closing tags: </ComponentName>
        $value = preg_replace_callback(
            '/<\/([A-Z][a-zA-Z0-9]*(?:\.[A-Z][a-zA-Z0-9]*)*)\s*>/',
            function (array $matches) {
                if ($this->isHtmlTag($matches[1])) {
                    return $matches[0];
                }
                $componentName = $this->convertPascalCaseToKebabCase($matches[1]);
                return "</x-{$componentName}>";
            },
            $value
        );

        return $value;
    }


    /**
     * Convert PascalCase to kebab-case.
     *
     * @param string $pascalCase
     * @return string
     */
    protected function convertPascalCaseToKebabCase(string $pascalCase): string
    {
        // Handle dot notation (Form.Input -> form.input)
        if (str_contains($pascalCase, '.')) {
            $parts = explode('.', $pascalCase);
            $parts = array_map(fn($part) => Str::kebab($part), $parts);
            return implode('.', $parts);
        }

        return Str::kebab($pascalCase);
    }

    /**
     * Check if the given tag is a standard HTML tag.
     *
     * For PascalCase components, we should only consider them HTML tags if they
     * start with lowercase (which they won't in our case since we only match PascalCase).
     * This method assumes we only call it with PascalCase component names.
     *
     * @param string $tag
     * @return bool
     */
    protected function isHtmlTag(string $tag): bool
    {
        // If the tag starts with uppercase, it's a PascalCase component, not HTML
        if (ctype_upper($tag[0])) {
            // Special case: if it's a single letter or looks like an HTML tag but capitalized
            // we still might want to transform it (like 'Div', 'Span', etc.)
            // For now, let's allow all PascalCase tags to be transformed
            return false;
        }

        return in_array(strtolower($tag), array_map('strtolower', $this->ignoreTags));
    }
}