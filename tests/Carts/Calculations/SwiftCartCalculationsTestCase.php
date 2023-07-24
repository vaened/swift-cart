<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Calculations;

use Brick\Money\Money;
use Vaened\SwiftCart\Tests\Carts\SwiftCartTestCase;
use Vaened\SwiftCart\Tests\Utils\Summary;

use function call_user_func;
use function dd;
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

        dd([
            'subtotal' => $summary->subtotal(),
            'totalTaxes' => $summary->totalTaxes(),
            'totalCharges' => $summary->totalCharges(),
            'totalDiscounts' => $summary->totalDiscounts(),
            'total' => $summary->total(),
        ]);
    }

    protected function assertTotals(Summary $summary): void
    {
        $this->assertTotal('subtotal', $summary->subtotal());
        $this->assertTotal('totalTaxes', $summary->totalTaxes());
        $this->assertTotal('totalCharges', $summary->totalCharges());
        $this->assertTotal('totalDiscounts', $summary->totalDiscounts());
        $this->assertTotal('total', $summary->total());
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
