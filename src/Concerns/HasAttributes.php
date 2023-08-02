<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Concerns;

trait HasAttributes
{
    private mixed $attributes = null;

    public function attributes(): mixed
    {
        return $this->attributes;
    }

    protected function setAttributes(mixed $attributes): void
    {
        $this->attributes = $attributes;
    }
}
