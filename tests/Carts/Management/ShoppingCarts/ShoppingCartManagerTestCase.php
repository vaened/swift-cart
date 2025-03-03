<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management\ShoppingCarts;

use PHPUnit\Framework\Attributes\Test;
use Vaened\PriceEngine\Adjustments\AdjustmentScheme;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\SwiftCart\Carts\ShoppingCart;
use Vaened\SwiftCart\Tests\Carts\Management\SwiftCartManagerTestCase;
use Vaened\SwiftCart\Tests\Utils\Products;

abstract class ShoppingCartManagerTestCase extends SwiftCartManagerTestCase
{
    abstract protected function cart(): ShoppingCart;

    #[Test]
    public function push_item_to_staging(): void
    {
        $this->cart()->push(Products::mouse());

        $this->assertCartContains(Products::mouse());
    }

    #[Test]
    public function remove_item_from_staging(): void
    {
        $this->cart()->push(Products::monitor());
        $this->cart()->remove(Products::monitor());

        $this->assertCartNotContains(Products::monitor());
    }

    #[Test]
    public function can_locate_specific_item_from_staging(): void
    {
        $this->cart()->push(Products::mouse());
        $mouse = $this->cart()->locate(Products::mouse());

        $this->assertCartItemIs(Products::mouse(), $mouse);
    }

    #[Test]
    public function add_charge_to_cart(): void
    {
        $this->cart()->addAsGlobal(Charge::proportional(10)->named('DELIVERY'));

        $this->assertCartHas(Charge::proportional(10)->named('DELIVERY'));
    }

    #[Test]
    public function remove_charge_from_cart(): void
    {
        $charge = Charge::proportional(20)->named('DELIVERY');
        $this->cart()->addAsGlobal($charge);

        $this->cart()->revertGlobalCharge('DELIVERY');

        $this->assertCartHasNot($charge);
    }

    #[Test]
    public function add_discount_to_cart(): void
    {
        $this->cart()->applyAsGlobal(Discount::proportional(20)->named('NEW_USERS'));

        $this->assertCartHas(Discount::proportional(20)->named('NEW_USERS'));
    }

    #[Test]
    public function remove_discount_from_cart(): void
    {
        $discount = Discount::proportional(20)->named('NEW_USERS');
        $this->cart()->applyAsGlobal($discount);

        $this->cart()->cancelGlobalDiscount('NEW_USERS');

        $this->assertCartHasNot($discount);
    }

    private function assertCartHas(Charge|Discount $adjuster): void
    {
        $model = $this->locateAdjusterFromCart($adjuster);
        $this->assertAdjusterEquals($adjuster, $model);
    }

    private function assertCartHasNot(Charge|Discount $adjuster): void
    {
        $model = $this->locateAdjusterFromCart($adjuster);
        $this->assertNull($model);
    }

    private function locateAdjusterFromCart(Charge|Discount $adjuster): ?AdjustmentScheme
    {
        return $adjuster instanceof Charge
            ? $this->cart()->globalCharges()->locate($adjuster->code())
            : $this->cart()->globalDiscounts()->locate($adjuster->code());
    }
}
