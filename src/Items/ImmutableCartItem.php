<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\PriceEngine\Adjustments\Tax\Taxes;
use Vaened\SwiftCart\Entities\CommercialTransactionItem;

final class ImmutableCartItem extends CartItem
{
    public function __construct(CommercialTransactionItem $commercialItem)
    {
        parent::__construct($commercialItem, $commercialItem->quantity(), Taxes::empty());
    }

    public function toCommerceable(): CommerceableCartItem
    {
        return new CommerceableCartItem(
            $this->tradable(),
            $this->quantity(),
        );
    }
}
