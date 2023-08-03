<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Quotation;

use Vaened\SwiftCart\Entities\CommercialTransactionItems;
use Vaened\SwiftCart\Entities\DraftCommercialTransaction;

final class Account implements DraftCommercialTransaction
{
    public function __construct(
        private readonly AccountDetailItems $items,
    )
    {
    }

    public static function create(AccountDetailItems $items): self
    {
        return new self($items);
    }

    public function items(): CommercialTransactionItems
    {
        return $this->items->toCommercialItems();
    }
}
