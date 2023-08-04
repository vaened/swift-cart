<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use Vaened\PriceEngine\Adjustment;
use Vaened\PriceEngine\Adjustments;
use Vaened\SwiftCart\Carts\SwiftCart;

use function Lambdish\Phunctional\each;

abstract class SwiftCartTestCase extends TestCase
{
    abstract protected function cart(): SwiftCart;

    protected function assertAdjustments(Adjustment ...$expected): void
    {
        $adjustments = self::collect([
            ...$this->cart()->globalCharges()->items(),
            ...$this->cart()->globalDiscounts()->items(),
        ]);

        each(
            $this->assertAdjustmentEquals($adjustments),
            self::collect($expected),
        );
    }

    private function assertAdjustmentEquals(Adjustments $adjustments): callable
    {
        return static function (Adjustment $expected) use ($adjustments) {
            $adjustment = $adjustments->locate($expected->code());

            self::assertNotNull(
                $adjustment,
                sprintf(
                    'Failed asserting that adjuster <%s> is in the cart.',
                    $expected->code()
                )
            );

            self::assertEquals($adjustment, $expected);
        };
    }
}
