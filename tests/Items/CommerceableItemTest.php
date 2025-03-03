<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Items;

use PHPUnit\Framework\Attributes\Test;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\PriceEngine\Adjustments\Taxation\Inclusive;
use Vaened\PriceEngine\Adjustments\Taxation\TaxCodes;
use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Carts\ShoppingCart;
use Vaened\SwiftCart\Tests\SwiftCartCalculationsTestCase;
use Vaened\SwiftCart\Tests\Utils\Carts;
use Vaened\SwiftCart\Tests\Utils\MoneyFactory;
use Vaened\SwiftCart\Tests\Utils\Products;
use Vaened\SwiftCart\Tests\Utils\Summary;
use Vaened\SwiftCart\Tests\Utils\TaxCode;

final class CommerceableItemTest extends SwiftCartCalculationsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Products::mouse(
            Amount::taxable(MoneyFactory::of(55), TaxCodes::any())
        );
        Products::monitor(
            Amount::taxable(MoneyFactory::of(679), TaxCodes::any())->impose([
                Inclusive::fixed(12, TaxCode::ISC)
            ])
        );
        Products::keyboard(
            Amount::taxable(MoneyFactory::of(200), TaxCodes::any())->impose([
                Inclusive::fixed(5, TaxCode::ISC)
            ])
        );
    }

    #[Test]
    public function add_charge_recalculates_totals(): void
    {
        $mouse = $this->cart()->push(Products::mouse());

        $mouse->add(
            Charge::proportional(2)->named('Delivery')
        );

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(55.0000),
                totalTaxes    : MoneyFactory::of(8.3898),
                totalCharges  : MoneyFactory::of(0.9322),
                totalDiscounts: MoneyFactory::of(0),
                total         : MoneyFactory::of(64.3220),
            )
        );
    }

    #[Test]
    public function apply_discount_recalculates_totals(): void
    {
        $mouse = $this->cart()->push(Products::keyboard());

        $mouse->apply(
            Discount::fixed(5)->named('NewUser')
        );

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(200.0000),
                totalTaxes    : MoneyFactory::of(34.7458),
                totalCharges  : MoneyFactory::of(0.0),
                totalDiscounts: MoneyFactory::of(5.0),
                total         : MoneyFactory::of(229.7458),
            )
        );
    }

    #[Test]
    public function update_quantity_recalculates_totals(): void
    {
        $monitor = $this->cart()->push(Products::monitor());

        $monitor->update(quantity: 2);

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(1358.0000),
                totalTaxes    : MoneyFactory::of(227.4916),
                totalCharges  : MoneyFactory::of(0.0),
                totalDiscounts: MoneyFactory::of(0.0),
                total         : MoneyFactory::of(1585.4916),
            )
        );
    }

    protected function cart(): ShoppingCart
    {
        return Carts::memoize(static fn(): ShoppingCart => new ShoppingCart(
            Taxes::from([
                Inclusive::proportional(18, TaxCode::IGV),
            ])
        ));
    }
}
