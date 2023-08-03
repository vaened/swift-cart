<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\PriceEngine\AdjustmentManager;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Entities\TradedCommercialTransaction;
use Vaened\SwiftCart\Items\ImmutableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;
use Vaened\SwiftCart\NotFoundItem;
use Vaened\SwiftCart\Summary;

final class SnapshotCart extends SwiftCart
{
    private readonly ImmutableCartItems $immutables;

    private readonly ImmutableCartItems $items;

    private readonly AdjustmentManager  $charges;

    private readonly AdjustmentManager  $discounts;

    public function __construct(TradedCommercialTransaction $transaction)
    {
        $this->immutables = $transaction->items()->toImmutables();
        $this->items      = new ImmutableCartItems([]);
        $this->charges    = $this->createManagerOf($transaction->charges());
        $this->discounts  = $this->createManagerOf($transaction->discounts());
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

    protected function globalChargesManager(): AdjustmentManager
    {
        return $this->charges;
    }

    protected function globalDiscountsManager(): AdjustmentManager
    {
        return $this->discounts;
    }

    private function ensureValidItem(?ImmutableCartItem $item): void
    {
        if (null === $item) {
            throw new NotFoundItem();
        }
    }
}
