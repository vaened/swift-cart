<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

final class Attributes
{
    public function __construct(private array $attributes)
    {
    }

    public static function are(array $attributes): self
    {
        return new self($attributes);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function has(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }
}
