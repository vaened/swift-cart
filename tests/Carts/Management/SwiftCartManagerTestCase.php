<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts\Management;

use Vaened\PriceEngine\Adjustments\{AdjusterScheme, Charge, Discount};
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Items\CartItem;
use Vaened\SwiftCart\Tests\SwiftCartTestCase;
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

        $this->assertAdjusterEquals($adjuster, $model);
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
        $tradable = $item?->tradable();
        $this->assertEquals(
            null === $tradable ? null : new Product(
                $tradable->uniqueId(),
                $tradable->amount(),
            ),
            $product
        );
    }

    protected function assertAdjusterEquals(AdjusterScheme $expected, AdjusterScheme $actual): void
    {
        $this->assertSame(
            ['code' => $expected->code(), 'value' => $expected->value(), 'type' => $expected->type()],
            ['code' => $actual->code(), 'value' => $actual->value(), 'type' => $actual->type()]
        );
    }
}
