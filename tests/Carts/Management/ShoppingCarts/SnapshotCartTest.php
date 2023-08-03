<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management\ShoppingCarts;

use Vaened\SwiftCart\Carts\SnapshotCart;
use Vaened\SwiftCart\Tests\Utils\Billier;
use Vaened\SwiftCart\Tests\Utils\Carts;
use Vaened\SwiftCart\Tests\Utils\Products;

final class SnapshotCartTest extends ShoppingCartManagerTestCase
{
    protected function cart(): SnapshotCart
    {
        return Carts::memoize(static fn(): SnapshotCart => new SnapshotCart(
            Billier::invoice([
                Products::generate(),
                Products::generate(),
            ])
        ));
    }
}
