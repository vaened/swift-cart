<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management\ShoppingCarts;

use Vaened\SwiftCart\Carts\OrderCart;
use Vaened\SwiftCart\Tests\Utils\Billier;
use Vaened\SwiftCart\Tests\Utils\Carts;
use Vaened\SwiftCart\Tests\Utils\Products;

final class OrderCartTest extends ShoppingCartManagerTestCase
{
    public function test_pull_item_from_transaction2(): void
    {
        $this->cart()->pull(Products::monitor());

        $this->assertCartContains(Products::monitor());
    }

    public function test_pull_all_items_from_transaction(): void
    {
        $this->cart()->pullAll();

        $this->assertCartContains(...Products::all());
    }

    public function test_cart_has_specific_item(): void
    {
        $item = $this->cart()->pull(Products::mouse());

        $this->assertTrue($this->cart()->has($item));
    }

    protected function cart(): OrderCart
    {
        return Carts::memoize(fn(): OrderCart => new OrderCart(
            Billier::account(Products::all())
        ));
    }
}
