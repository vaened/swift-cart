<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

use Vaened\Support\Types\SecureList;
use Vaened\SwiftCart\Items\ImmutableCartItem;
use Vaened\SwiftCart\Items\ImmutableCartItems;

final class CommercialTransactionItems extends SecureList
{
    public function toImmutables(): ImmutableCartItems
    {
        return new ImmutableCartItems(
            $this->map(CommercialTransactionItems::toImmutable())
        );
    }

    public static function type(): string
    {
        return CommercialTransactionItem::class;
    }

    private static function toImmutable(): callable
    {
        return static fn(CommercialTransactionItem $traded) => new ImmutableCartItem($traded);
    }
}
