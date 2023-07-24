<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management;

use Vaened\PriceEngine\Money\Charge;
use Vaened\PriceEngine\Money\Discount;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Items\CartItem;
use Vaened\SwiftCart\Tests\Carts\SwiftCartTestCase;
use Vaened\SwiftCart\Tests\Utils\Product;

use function Lambdish\Phunctional\map;

abstract class SwiftCartManagerTestCase extends SwiftCartTestCase
{
    protected function assertCartItemsHas(Charge|Discount $adjuster): void
    {
        $this->cart()->items()->each(function (CartItem $item) use ($adjuster) {
            $this->assertCartItemHas($item, $adjuster);
        });
    }

    protected function assertCartItemHas(Identifiable $identifiable, Charge|Discount $adjuster): void
    {
        $item = $this->cart()->locate($identifiable);

        $model = $adjuster instanceof Charge
            ? $item->summary()->charges()->locate($adjuster->code())
            : $item->summary()->discounts()->locate($adjuster->code());

        $this->assertSame(
            ['code' => $model->code(), 'value' => $model->value(), 'type' => $model->type()],
            ['code' => $adjuster->code(), 'value' => $adjuster->value(), 'type' => $adjuster->type()]
        );
    }

    protected function assertCartContains(Product ...$products): void
    {
        map(function (Product $product) {
            $item = $this->cart()
                         ->items()
                         ->find(static fn(CartItem $item) => $item->uniqueId() === $product->uniqueId());

            $this->assertCartItemIs($product, $item);
        }, $products);
    }

    protected function assertCartNotContains(Product $product): void
    {
        $this->assertFalse(
            $this->cart()
                 ->items()
                 ->some(static fn(CartItem $item) => $item->uniqueId() === $product->uniqueId())
        );
    }

    protected function assertCartItemIs(Product $product, ?CartItem $item): void
    {
        $this->assertEquals(
            null === $item ? null : new Product(
                $item->uniqueId(),
                $item->amount(),
            ),
            $product
        );
    }
}
