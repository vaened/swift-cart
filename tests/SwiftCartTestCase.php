<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use Vaened\PriceEngine\Modifier;
use Vaened\PriceEngine\Modifiers;
use Vaened\SwiftCart\Carts\SwiftCart;

use function Lambdish\Phunctional\each;

abstract class SwiftCartTestCase extends TestCase
{
    abstract protected function cart(): SwiftCart;

    protected function assertAdjustments(Modifier ...$expected): void
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

    private function assertAdjustmentEquals(Modifiers $modifiers): callable
    {
        return static function (Modifier $expected) use ($modifiers) {
            $adjustment = $modifiers->locate($expected->code());

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
