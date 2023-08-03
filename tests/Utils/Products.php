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
    public static array $items = [];

    public static function generate(?Amount $amount = null): Product
    {
        return Product::create($amount ?? Amount::taxable(MoneyFactory::random()));
    }

    public static function monitor(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= self::generate($amount);
    }

    public static function keyboard(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= self::generate($amount);
    }

    public static function mouse(Amount $amount = null): Product
    {
        return self::$items[__FUNCTION__] ??= self::generate($amount);
    }

    public static function all(): array
    {
        $reflection = new ReflectionClass(self::class);
        $methods    = $reflection->getMethods(ReflectionMethod::IS_STATIC);

        return map(
            static fn(ReflectionMethod $method): Product => $method->invoke(null),
            filter(
                self::onlyNamedProductGenerator(),
                $methods,
            )
        );
    }

    public static function clean(): void
    {
        self::$items = [];
    }

    private static function onlyNamedProductGenerator(): callable
    {
        return static function (ReflectionMethod $method): bool {
            return $method->getName() !== 'generate' && $method->getReturnType()->getName() === Product::class;
        };
    }
}
