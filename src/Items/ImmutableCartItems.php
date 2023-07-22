<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\SwiftCart\Entities\Identifiable;

use function in_array;

final class ImmutableCartItems extends CartItems
{
    public function locate(Identifiable $item): ?ImmutableCartItem
    {
        return $this->items[$item->uniqueId()];
    }

    public function push(ImmutableCartItem $item): void
    {
        $this->attach($item);
    }

    public function toCommerceables(): CommerceableCartItems
    {
        return new CommerceableCartItems(
            $this->map(static fn(ImmutableCartItem $item) => $item->toCommerceable())
        );
    }

    public function except(CartItems $items): self
    {
        $itemIds = $items->ids();

        return $this->filter(
            static fn(ImmutableCartItem $item) => !in_array($item->uniqueId(), $itemIds)
        );
    }

    public function combine(self $items): void
    {
        $items->each($this->add());
    }

    protected function type(): string
    {
        return ImmutableCartItem::class;
    }

    private function add(): callable
    {
        return fn(ImmutableCartItem $item) => $this->push($item);
    }
}
