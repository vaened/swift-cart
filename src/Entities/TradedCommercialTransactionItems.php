<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

use Vaened\Support\Types\ArrayObject;
use Vaened\SwiftCart\Items\ImmutableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;

final class TradedCommercialTransactionItems extends ArrayObject
{
    public function toImmutables(): ImmutableCartItems
    {
        return new ImmutableCartItems(
            $this->map(TradedCommercialTransactionItems::toImmutable())
        );
    }

    protected function type(): string
    {
        return Traded::class;
    }

    private static function toImmutable(): callable
    {
        return static fn(Traded $traded) => new ImmutableCartItem($traded);
    }
}
