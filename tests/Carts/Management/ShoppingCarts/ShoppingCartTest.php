<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management\ShoppingCarts;

use Vaened\SwiftCart\Carts\ShoppingCart;
use Vaened\SwiftCart\Tests\Utils\Carts;

final class ShoppingCartTest extends ShoppingCartManagerTestCase
{
    protected function cart(): ShoppingCart
    {
        return Carts::memoize(fn(): ShoppingCart => new ShoppingCart());
    }
}
