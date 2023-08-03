<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use ReflectionFunction;

final class Carts
{
    private static array $cart = [];

    public static function memoize(callable $cartGenerator): mixed
    {
        $reflection = new ReflectionFunction($cartGenerator);
        $cart       = $reflection->getReturnType()?->getName() ?? 'swift-cart';

        return self::$cart[$cart] ??= $reflection->invoke();
    }

    public static function clean(): void
    {
        self::$cart = [];
    }
}
