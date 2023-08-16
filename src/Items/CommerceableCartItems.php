<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\SwiftCart\Entities\Identifiable;

final class CommerceableCartItems extends CartItems
{
    public function locate(Identifiable $identifiable): ?CommerceableCartItem
    {
        return $this->items[$identifiable->uniqueId()];
    }

    public function push(CommerceableCartItem $item): void
    {
        $this->attach($item);
    }

    public function add(array $charges): void
    {
        $this->each(static fn(CommerceableCartItem $item) => $item->add(...$charges));
    }

    public function apply(array $discounts): void
    {
        $this->each(static fn(CommerceableCartItem $item) => $item->apply(...$discounts));
    }

    protected static function type(): string
    {
        return CommerceableCartItem::class;
    }
}
