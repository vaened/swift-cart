<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\PriceEngine\AdjustmentManager;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Entities\RegisteredCommercialTransaction;
use Vaened\SwiftCart\Items\ImmutableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;

/**
 * Display cart
 *
 * This class represents a consolidated transaction.
 * Simply load a transaction with discounts and charges to
 * perform the corresponding calculations
 */
final class ShowcasedCart extends SwiftCart
{
    private readonly ImmutableCartItems $items;

    private readonly AdjustmentManager  $charges;

    private readonly AdjustmentManager  $discounts;

    public function __construct(RegisteredCommercialTransaction $transaction)
    {
        $this->items     = $transaction->items()->toImmutables();
        $this->charges   = $this->createManagerOf($transaction->charges());
        $this->discounts = $this->createManagerOf($transaction->discounts());
    }

    public function locate(Identifiable $identifiable): ?ImmutableCartItem
    {
        return $this->staging()->locate($identifiable);
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
}
