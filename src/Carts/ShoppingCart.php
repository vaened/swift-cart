<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use BackedEnum;
use UnitEnum;
use Vaened\PriceEngine\AdjustmentManager;
use Vaened\PriceEngine\Adjustments\{Adjusters, Charge, Discount, Tax\Taxes};
use Vaened\SwiftCart\Entities\{Identifiable, Tradable};
use Vaened\SwiftCart\Items\{CommerceableCartItem, CommerceableCartItems};

/**
 * Common shopping cart
 *
 * This class represents a shopping cart that is commonly used in stores,
 * it is used to generate quotes, invoices, accounts, etc.
 * Allows you to add products, discounts, charges and taxes as needed.
 */
class ShoppingCart extends SwiftCart
{
    private readonly CommerceableCartItems $items;

    private readonly AdjustmentManager     $charges;

    private readonly AdjustmentManager     $discounts;

    public function __construct(
        private readonly Taxes $taxes = new Taxes([])
    )
    {
        $this->items     = new CommerceableCartItems();
        $this->charges   = $this->createManagerOf(Adjusters::empty());
        $this->discounts = $this->createManagerOf(Adjusters::empty());
    }

    public function addAsGlobal(Charge ...$charges): void
    {
        $this->charges->add($charges);
    }

    public function revertGlobalCharge(BackedEnum|UnitEnum|string $chargeCode): void
    {
        $this->charges->remove($chargeCode);
    }

    public function applyAsGlobal(Discount ...$discounts): void
    {
        $this->discounts->add($discounts);
    }

    public function cancelGlobalDiscount(BackedEnum|UnitEnum|string $chargeCode): void
    {
        $this->discounts->remove($chargeCode);
    }

    public function push(Tradable $quotable, int $quantity = 1): CommerceableCartItem
    {
        $item = new CommerceableCartItem($quotable, $quantity, $this->taxes);
        $this->attach($item);
        return $item;
    }

    public function locate(Identifiable $identifiable): ?CommerceableCartItem
    {
        return $this->staging()->locate($identifiable);
    }

    protected function staging(): CommerceableCartItems
    {
        return $this->items;
    }

    protected function attach(CommerceableCartItem $item): void
    {
        $this->staging()->push($item);
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
