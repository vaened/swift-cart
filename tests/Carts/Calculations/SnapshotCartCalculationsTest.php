<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Calculations;

use Vaened\PriceEngine\Adjusters\Adjusters;
use Vaened\PriceEngine\Adjusters\Tax;
use Vaened\PriceEngine\Adjusters\Tax\TaxCodes;
use Vaened\PriceEngine\Money\Amount;
use Vaened\PriceEngine\Money\Charge;
use Vaened\PriceEngine\Money\Discount;
use Vaened\SwiftCart\Carts\SnapshotCart;
use Vaened\SwiftCart\Tests\Utils\Billier;
use Vaened\SwiftCart\Tests\Utils\Billing\InvoiceDetailItem;
use Vaened\SwiftCart\Tests\Utils\MoneyFactory;
use Vaened\SwiftCart\Tests\Utils\Products;
use Vaened\SwiftCart\Tests\Utils\Summary;
use Vaened\SwiftCart\Tests\Utils\TaxCode;

final class SnapshotCartCalculationsTest extends SwiftCartCalculationsTestCase
{
    private readonly SnapshotCart $swiftCart;

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
                subtotal      : MoneyFactory::of(854.2373),
                totalTaxes    : MoneyFactory::of(135.7627),
                totalCharges  : MoneyFactory::of(10),
                totalDiscounts: MoneyFactory::of(37.7119),
                total         : MoneyFactory::of(962.2881),
            )
        );
    }

    public function test_pull_all_items_recalculates_totals(): void
    {
        $this->cart()->pullAll();

        $this->assertTotals(
            new Summary(
                subtotal      : MoneyFactory::of(1574.2373),
                totalTaxes    : MoneyFactory::of(145.7627),
                totalCharges  : MoneyFactory::of(10),
                totalDiscounts: MoneyFactory::of(37.7119),
                total         : MoneyFactory::of(1692.2881),
            )
        );
    }

    public function test_remove_item_recalculates_total(): void
    {
        $this->cart()->pull(Products::keyboard());
        $this->cart()->remove(Products::keyboard());

        $this->assertTotals(Summary::zero());
    }

    protected function cart(): SnapshotCart
    {
        return $this->swiftCart ??= new SnapshotCart(
            Billier::invoice([
                InvoiceDetailItem::create(
                    Products::mouse(),
                    Amount::taxexempt(MoneyFactory::of(50)),
                    quantity: 2,
                    charges : Adjusters::from([
                        Charge::proporcional(10)
                    ])
                ),
                InvoiceDetailItem::create(
                    Products::monitor(),
                    Amount::taxable(MoneyFactory::of(890), TaxCodes::any())->impose([
                        Tax\Inclusive::proporcional(18, TaxCode::IGV),
                    ]),
                    discounts: Adjusters::from([
                        Discount::proporcional(5)
                    ])
                ),
                InvoiceDetailItem::create(
                    Products::keyboard(),
                    Amount::taxable(MoneyFactory::of(250), TaxCodes::only([TaxCode::ISC]))->impose([
                        Tax\Inclusive::fixed(10, TaxCode::ISC),
                        Tax\Inclusive::proporcional(18, TaxCode::IGV),
                    ]),
                    quantity: 3
                ),
            ])
        );
    }
}
