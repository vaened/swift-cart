<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Calculations;

use Vaened\PriceEngine\Adjustments\{Adjustments, Charge, Discount, Taxation, Taxation\TaxCodes};
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Carts\OrderCart;
use Vaened\SwiftCart\Tests\SwiftCartCalculationsTestCase;
use Vaened\SwiftCart\Tests\Utils\{MoneyFactory, Products, Quotation\AccountDetailItem, Summary, TaxCode};
use Vaened\SwiftCart\Tests\Utils\Billier;

final class OrderCartCalculationsTest extends SwiftCartCalculationsTestCase
{
    private readonly OrderCart $swiftCart;

    public function test_pull_item_recalculates_totals(): void
    {
        $this->cart()->pull(Products::mouse());

        $this->assertTotals(
            new Summary(
                subtotal    : MoneyFactory::of(100),
                totalCharges: MoneyFactory::of(10),
                total       : MoneyFactory::of(110),
            )
        );

        $this->cart()->pull(Products::monitor());

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(990.0000),
                totalTaxes    : MoneyFactory::of(135.7627),
                totalCharges  : MoneyFactory::of(10),
                totalDiscounts: MoneyFactory::of(37.7119),
                total         : MoneyFactory::of(1098.0508),
            )
        );
    }

    public function test_pull_all_items_recalculates_totals(): void
    {
        $this->cart()->pullAll();

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(1740.0000),
                totalTaxes    : MoneyFactory::of(165.7627),
                totalCharges  : MoneyFactory::of(10),
                totalDiscounts: MoneyFactory::of(37.7119),
                total         : MoneyFactory::of(1878.0508),
            )
        );
    }

    public function test_remove_item_recalculates_total(): void
    {
        $this->cart()->pull(Products::keyboard());
        $this->cart()->remove(Products::keyboard());

        $this->assertTotals(Summary::zero());
    }

    protected function cart(): OrderCart
    {
        return $this->swiftCart ??= new OrderCart(
            Billier::account([
                AccountDetailItem::create(
                    Products::mouse(),
                    Amount::taxexempt(MoneyFactory::of(50)),
                    quantity: 2,
                    charges : Adjustments::from([
                        Charge::proportional(10)
                    ])
                ),
                AccountDetailItem::create(
                    Products::monitor(),
                    Amount::taxable(MoneyFactory::of(890), TaxCodes::any())->impose([
                        Taxation\Inclusive::proportional(18, TaxCode::IGV),
                    ]),
                    discounts: Adjustments::from([
                        Discount::proportional(5)
                    ])
                ),
                AccountDetailItem::create(
                    Products::keyboard(),
                    Amount::taxable(MoneyFactory::of(250), TaxCodes::only([TaxCode::ISC]))->impose([
                        Taxation\Inclusive::fixed(10, TaxCode::ISC),
                        Taxation\Inclusive::proportional(18, TaxCode::IGV),
                    ]),
                    quantity: 3
                ),
            ])
        );
    }
}
