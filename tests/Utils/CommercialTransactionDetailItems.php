<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\Support\Types\ArrayObject;
use Vaened\SwiftCart\Entities\CommercialTransactionItems;

abstract class CommercialTransactionDetailItems extends ArrayObject
{
    public static function from(array $items): static
    {
        return new static($items);
    }

    public function toCommercialItems(): CommercialTransactionItems
    {
        return new CommercialTransactionItems(
            $this->values()
        );
    }
}
