<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\Support\Types\ImmutableCollection;
use Vaened\Support\Types\InvalidType;
use Vaened\SwiftCart\AlreadyAttachedItem;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Totalizer;

use function array_map;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\filter;
use function sprintf;

abstract class CartItems extends ImmutableCollection
{
    public function __construct(array $items = [])
    {
        parent::__construct([]);
        $this->reindex($items);
    }

    abstract protected function type(): string;

    public function ids(): array
    {
        return $this->map(static fn(CartItem $item) => $item->uniqueId());
    }

    public function has(Identifiable $identifiable): bool
    {
        return isset($this->items[$identifiable->uniqueId()]);
    }

    public function remove(Identifiable $identifiable): void
    {
        $this->items = filter(
            static fn(Identifiable $item) => $item->uniqueId() !== $identifiable->uniqueId(),
            $this->items
        );
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    public function totalizer(): Totalizer
    {
        return Totalizer::of(
            $this->map($this->summaries())
        );
    }

    protected function attach(CartItem $item): void
    {
        $this->ensureNotAddedBefore($item);
        $this->items[$item->uniqueId()] = $item;
    }

    private function reindex(array $items): void
    {
        each(function (mixed $item) {
            $this->ensureType($item);
            $this->attach($item);
        }, $items);
    }

    private function ensureType(mixed $item): void
    {
        $type = $this->type();
        if (!$item instanceof CartItem || !$item instanceof $type) {
            throw new InvalidType(static::class, sprintf('%s child of %s', $type, CartItem::class), $item::class);
        }
    }

    private function summaries(): callable
    {
        return static fn(CartItem $item) => $item->summary();
    }

    private function ensureNotAddedBefore(CartItem $item): void
    {
        if ($this->has($item)) {
            throw AlreadyAttachedItem::is($item->tradable());
        }
    }
}