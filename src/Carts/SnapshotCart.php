<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\PriceEngine\Adjustments\Tax\Taxes;
use Vaened\SwiftCart\Entities\RegisteredCommercialTransaction;
use Vaened\SwiftCart\Items\CommerceableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;

final class SnapshotCart extends ShoppingCart
{
    public function __construct(RegisteredCommercialTransaction $transaction, Taxes $taxes = new Taxes([]))
    {
        parent::__construct($taxes);
        $this->pullAll($transaction->items()->toImmutables());
        $this->addAsGlobal(...$transaction->charges()->items());
        $this->applyAsGlobal(...$transaction->discounts()->items());
    }

    private function pullAll(ImmutableCartItems $items): void
    {
        $items->toCommerceables()
              ->each($this->attachAll());
    }

    private function attachAll(): callable
    {
        return fn(CommerceableCartItem $commerceable) => $this->attach($commerceable);
    }
}
