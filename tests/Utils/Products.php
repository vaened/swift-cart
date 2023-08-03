<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use ReflectionClass;
use ReflectionMethod;
use Vaened\PriceEngine\Money\Amount;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\map;

final class Products
{
    private static array $items = [];

    public static function monitor(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= Product::create($amount ?? Amount::taxable(MoneyFactory::random()));
    }

    public static function keyboard(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= Product::create($amount ?? Amount::taxable(MoneyFactory::random()));
    }

    public static function mouse(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= Product::create($amount ?? Amount::taxable(MoneyFactory::random()));
    }

    public static function all(): array
    {
        $reflection = new ReflectionClass(self::class);
        $methods    = $reflection->getMethods(ReflectionMethod::IS_STATIC);

        return filter(
            static fn(?Product $product) => null != $product,
            map(
                static fn(
                    ReflectionMethod $method
                ) => $method->getReturnType()->getName() === Product::class ? $method->invoke(null) : null,
                $methods
            )
        );
    }

    public static function clean(): void
    {
        self::$items = [];
    }
}
