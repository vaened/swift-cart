<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management;

use Vaened\SwiftCart\Carts\SnapshotCart;
use Vaened\SwiftCart\Tests\Utils\Billier;
use Vaened\SwiftCart\Tests\Utils\Products;

final class SnapshotCartTest extends SwiftCartManagerTestCase
{
    private readonly SnapshotCart $swiftCart;

    public function test_pull_item_from_transaction(): void
    {
        $this->cart()->pull(Products::monitor());

        $this->assertCartContains(Products::monitor());
    }

    public function test_remove_item_from_staging(): void
    {
        $this->cart()->pull(Products::keyboard());
        $this->assertCartContains(Products::keyboard());

        $this->cart()->remove(Products::keyboard());
        $this->assertCartNotContains(Products::keyboard());
    }

    public function test_pull_all_items_from_transaction(): void
    {
        $this->cart()->pullAll();

        $this->assertCartContains(...Products::all());
    }

    public function test_can_locate_specific_item_from_staging(): void
    {
        $item = $this->cart()->pull(Products::mouse());

        $this->assertCartItemIs(Products::mouse(), $item);
    }

    public function test_cart_has_specific_item(): void
    {
        $item = $this->cart()->pull(Products::mouse());

        $this->assertTrue($this->cart()->has($item));
    }

    protected function cart(): SnapshotCart
    {
        return $this->swiftCart ??= new SnapshotCart(
            Billier::invoice(Products::all())
        );
    }
}
