<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\Support\Types\AbstractList;
use Vaened\Support\Types\InvalidSafelistItem;
use Vaened\Support\Types\InvalidType;
use Vaened\Support\Types\SecureList;
use Vaened\SwiftCart\AlreadyAttachedItem;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Totalizer;

use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\filter;
use function sprintf;

abstract class CartItems extends SecureList
{
    public function __construct(iterable $items = [])
    {
        parent::__construct(AbstractList::Empty);
        $this->reindex($items);
    }

    abstract public static function type(): string;

    public function ids(): array
    {
        return $this->map(static fn(CartItem $item) => $item->uniqueId())->items();
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

    private function reindex(iterable $items): void
    {
        each(function (mixed $item) {
            $this->ensure($item);
            $this->attach($item);
        }, $items);
    }

    private function ensure(mixed $item): void
    {
        $type = $this->type();
        if (!$item instanceof CartItem || !$item instanceof $type) {
            throw new InvalidSafelistItem(static::class, sprintf('%s child of %s', $type, CartItem::class), $item::class);
        }
    }
}