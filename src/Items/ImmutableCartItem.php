<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\PriceEngine\Adjustments\Tax\Taxes;
use Vaened\SwiftCart\Entities\Traded;

final class ImmutableCartItem extends CartItem
{
    public function __construct(Traded $quoted)
    {
        parent::__construct($quoted, $quoted->quantity(), Taxes::empty());
    }

    public function toCommerceable(): CommerceableCartItem
    {
        return new CommerceableCartItem(
            $this->tradable(),
            $this->quantity(),
        );
    }
}
