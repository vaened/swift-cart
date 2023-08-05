<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Items;

use PHPUnit\Framework\Attributes\Test;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\PriceEngine\Adjustments\Tax\Inclusive;
use Vaened\PriceEngine\Adjustments\Tax\TaxCodes;
use Vaened\PriceEngine\Adjustments\Tax\Taxes;
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
    #[Test]
    public function add_charge_recalculates_totals(): void
    {
        $mouse = $this->cart()->push(Products::mouse());

        $mouse->add(
            Charge::proporcional(2)->named('Delivery')
        );

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(46.6102),
                totalTaxes    : MoneyFactory::of(8.3898),
                totalCharges  : MoneyFactory::of(0.9322),
                totalDiscounts: MoneyFactory::of(0),
                total         : MoneyFactory::of(55.9322),
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
                subtotal      : MoneyFactory::of(165.2542),
                totalTaxes    : MoneyFactory::of(34.7458),
                totalCharges  : MoneyFactory::of(0.0),
                totalDiscounts: MoneyFactory::of(5.0),
                total         : MoneyFactory::of(195.0),
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
                subtotal      : MoneyFactory::of(1130.5084),
                totalTaxes    : MoneyFactory::of(227.4916),
                totalCharges  : MoneyFactory::of(0.0),
                totalDiscounts: MoneyFactory::of(0.0),
                total         : MoneyFactory::of(1358.0),
            )
        );
    }

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

    protected function cart(): ShoppingCart
    {
        return Carts::memoize(static fn(): ShoppingCart => new ShoppingCart(
            Taxes::from([
                Inclusive::proporcional(18, TaxCode::IGV),
            ])
        ));
    }
}
