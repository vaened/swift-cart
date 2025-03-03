<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Calculations;

use PHPUnit\Framework\Attributes\Test;
use Vaened\PriceEngine\Adjustments\{Charge, Discount};
use Vaened\PriceEngine\Adjustments\Taxation;
use Vaened\PriceEngine\Adjustments\Taxation\{Inclusive, TaxCodes, Taxes};
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Carts\ShoppingCart;
use Vaened\SwiftCart\Tests\SwiftCartCalculationsTestCase;
use Vaened\SwiftCart\Tests\Utils\{Carts, MoneyFactory, Products, Summary, TaxCode};

final class ShoppingCartCalculationTest extends SwiftCartCalculationsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Products::mouse(
            Amount::taxable(MoneyFactory::of(55), TaxCodes::only([TaxCode::ISC]))->impose([
                Taxation\Inclusive::fixed(4, TaxCode::ISC),
            ])
        )->with(
            Discount::proportional(3)
        );

        Products::monitor(
            Amount::taxable(MoneyFactory::of(760), TaxCodes::only([TaxCode::IGV, TaxCode::ISC]))->impose([
                Taxation\Inclusive::proportional(2, TaxCode::ISC),
            ])
        )->with(
            Discount::proportional(5),
            Charge::proportional(6),
        );

        Products::keyboard(
            Amount::taxable(MoneyFactory::of(185), TaxCodes::none())
        )->with(
            Charge::proportional(13),
        );
    }

    #[Test]
    public function push_item_recalculates_totals(): void
    {
        $this->cart()->push(Products::mouse(), quantity: 3);

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(165.0000),
                totalTaxes    : MoneyFactory::of(12.0),
                totalCharges  : MoneyFactory::of(3.2082),
                totalDiscounts: MoneyFactory::of(12.6105),
                total         : MoneyFactory::of(167.5977),
            )
        );

        $this->cart()->push(Products::monitor(), quantity: 2);

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(1685.0000),
                totalTaxes    : MoneyFactory::of(265.3334),
                totalCharges  : MoneyFactory::of(109.8615),
                totalDiscounts: MoneyFactory::of(152.5772),
                total         : MoneyFactory::of(1907.6177),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(33.8615, Charge::proportional(2)->named('WEB')),
            self::createAdjustment(84.6538, Discount::proportional(5)->named('PROMOTIONAL')),
        );
    }

    #[Test]
    public function remove_item_recalculates_totals(): void
    {
        $this->cart()->push(Products::monitor(), quantity: 2);
        $this->cart()->push(Products::mouse(), quantity: 3);
        $this->cart()->remove(Products::monitor());

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(165.0000),
                totalTaxes    : MoneyFactory::of(12.0),
                totalCharges  : MoneyFactory::of(3.2082),
                totalDiscounts: MoneyFactory::of(12.6105),
                total         : MoneyFactory::of(167.5977),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(3.2082, Charge::proportional(2)->named('WEB')),
            self::createAdjustment(8.0205, Discount::proportional(5)->named('PROMOTIONAL')),
        );
    }

    #[Test]
    public function add_global_charge_recalculates_totals(): void
    {
        $this->cart()->push(Products::keyboard(), 3);

        $this->cart()->addAsGlobal(
            Charge::proportional(20)->named('FOR-TESTS')
        );

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(555.0),
                totalTaxes    : MoneyFactory::of(0.0),
                totalCharges  : MoneyFactory::of(210.1230),
                totalDiscounts: MoneyFactory::of(31.3575),
                total         : MoneyFactory::of(733.7655),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(12.543, Charge::proportional(2)->named('WEB')),
            self::createAdjustment(31.3575, Discount::proportional(5)->named('PROMOTIONAL')),
            self::createAdjustment(125.43, Charge::proportional(20)->named('FOR-TESTS')),
        );
    }

    #[Test]
    public function remove_global_charge_recalculates_totals(): void
    {
        $this->cart()->push(Products::monitor(), 3);
        $this->cart()->addAsGlobal(Charge::proportional(20)->named('FOR-TESTS'));

        $this->cart()->revertGlobalCharge('WEB');

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(2280.0000),
                totalTaxes    : MoneyFactory::of(380.0001),
                totalCharges  : MoneyFactory::of(573.8),
                totalDiscounts: MoneyFactory::of(209.9501),
                total         : MoneyFactory::of(3023.8500),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(114.95, Discount::proportional(5)->named('PROMOTIONAL')),
            self::createAdjustment(459.8, Charge::proportional(20)->named('FOR-TESTS')),
        );
    }

    #[Test]
    public function add_global_discount_recalculates_totals(): void
    {
        $this->cart()->push(Products::mouse(), 2);

        $this->cart()->applyAsGlobal(
            Discount::proportional(12)->named('FOR-TESTS')
        );

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(110.0000),
                totalTaxes    : MoneyFactory::of(8.0),
                totalCharges  : MoneyFactory::of(2.1388),
                totalDiscounts: MoneyFactory::of(21.2398),
                total         : MoneyFactory::of(98.8990),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(2.1388, Charge::proportional(2)->named('WEB')),
            self::createAdjustment(5.3470, Discount::proportional(5)->named('PROMOTIONAL')),
            self::createAdjustment(12.8328, Discount::proportional(12)->named('FOR-TESTS')),
        );
    }

    #[Test]
    public function remove_global_discount_recalculates_totals(): void
    {
        $this->cart()->push(Products::mouse(), 2);
        $this->cart()->applyAsGlobal(Discount::proportional(12)->named('FOR-TESTS'));

        $this->cart()->cancelGlobalDiscount('PROMOTIONAL');

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(110.0000),
                totalTaxes    : MoneyFactory::of(8.0),
                totalCharges  : MoneyFactory::of(2.1388),
                totalDiscounts: MoneyFactory::of(15.8928),
                total         : MoneyFactory::of(104.2460),
            )
        );

        $this->assertAdjustments(
            self::createAdjustment(2.1388, Charge::proportional(2)->named('WEB')),
            self::createAdjustment(12.8328, Discount::proportional(12)->named('FOR-TESTS')),
        );
    }

    protected function cart(): ShoppingCart
    {
        return Carts::memoize(static function (): ShoppingCart {
            $cart = new ShoppingCart(
                Taxes::from([
                    Inclusive::proportional(18, TaxCode::IGV),
                ])
            );

            $cart->addAsGlobal(Charge::proportional(2)->named('WEB'));
            $cart->applyAsGlobal(Discount::proportional(5)->named('PROMOTIONAL'));

            return $cart;
        });
    }
}
