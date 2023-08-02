<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Entities\TradedCommercialTransaction;
use Vaened\SwiftCart\Items\ImmutableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;
use Vaened\SwiftCart\NotFoundItem;
use Vaened\SwiftCart\Summary;

final class SnapshotCart extends SwiftCart
{
    private readonly ImmutableCartItems $immutables;

    private ImmutableCartItems          $items;

    public function __construct(TradedCommercialTransaction $transaction)
    {
        $this->immutables = $transaction->items()->toImmutables();
        $this->items      = new ImmutableCartItems([]);
    }

    public function locate(Identifiable $identifiable): ?ImmutableCartItem
    {
        return $this->staging()->locate($identifiable);
    }

    public function pull(Identifiable $item): ImmutableCartItem
    {
        $immutableItem = $this->immutables->locate($item);
        $this->ensureValidItem($immutableItem);

        $this->staging()->push($immutableItem);

        return $immutableItem;
    }

    public function pullAll(): void
    {
        $immutables = $this->immutables->except($this->staging());
        $this->staging()->combine($immutables);
    }

    public function summary(): Summary
    {
        return $this->totalizer()->summary();
    }

    protected function staging(): ImmutableCartItems
    {
        return $this->items;
    }

    private function ensureValidItem(?ImmutableCartItem $item): void
    {
        if (null === $item) {
            throw new NotFoundItem();
        }
    }
}
