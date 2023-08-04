<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use Brick\Money\Money;
use Vaened\SwiftCart\Items\CartItem;
use Vaened\SwiftCart\Tests\Utils\Summary;

use function call_user_func;
use function dd;
use function Lambdish\Phunctional\reduce;
use function sprintf;

abstract class SwiftCartCalculationsTestCase extends SwiftCartTestCase
{
    public function test_initial_state_is_zero(): void
    {
        $this->assertTotals(Summary::zero());

        $this->assertEmpty($this->cart()->items());
    }

    protected function printTotals(): never
    {
        $summary = $this->cart()->summary();

        dd(
            reduce(function (array $acc, Money $total, string $label) {
                $acc[$label] = $total->getAmount();
                return $acc;
            },
                [
                    'subtotal'       => $summary->subtotal(),
                    'totalTaxes'     => $summary->totalTaxes(),
                    'totalCharges'   => $summary->totalCharges(),
                    'totalDiscounts' => $summary->totalDiscounts(),
                    'total'          => $summary->total(),
                ],
                []),

        );
    }

    protected function assertTotals(Summary $summary): void
    {
        $this->assertTotal('subtotal', $summary->subtotal());
        $this->assertTotal('totalTaxes', $summary->totalTaxes());
        $this->assertTotal('totalCharges', $summary->totalCharges());
        $this->assertTotal('totalDiscounts', $summary->totalDiscounts());
        $this->assertTotal('total', $summary->total());
    }

    protected function printIndividualTotals(): never
    {
        dd($this->cart()->items()->reduce(function (array $summary, CartItem $item) {
            $cashier = $item->summary();

            $summary['unitPrice'] = $cashier->unitPrice()
                                            ->gross()
                                            ->plus($summary['unitPrice'] ?? 0)
                                            ->getAmount();
            $summary['subtotal']  = $cashier->subtotal()
                                            ->gross()
                                            ->plus($summary['subtotal'] ?? 0)
                                            ->getAmount();
            $summary['taxes']     = $cashier->taxes()
                                            ->total()
                                            ->plus($summary['taxes'] ?? 0)
                                            ->getAmount();
            $summary['charges']   = $cashier->charges()
                                            ->total()
                                            ->plus($summary['charges'] ?? 0)
                                            ->getAmount();
            $summary['discounts'] = $cashier->discounts()
                                            ->total()
                                            ->plus($summary['discounts'] ?? 0)
                                            ->getAmount();
            $summary['total']     = $cashier->total()
                                            ->plus($summary['total'] ?? 0)
                                            ->getAmount();

            return $summary;
        }, []));
    }

    private function assertTotal(string $label, Money $expected): void
    {
        $total = call_user_func([$this->cart()->summary(), $label]);

        $this->assertEquals(
            $expected,
            $total,
            sprintf(
                'Failed asserting that the result <%s> of Cart::%s() is equal to <%s>.',
                $total->getAmount(),
                $label,
                $expected->getAmount()
            )
        );
    }
}
