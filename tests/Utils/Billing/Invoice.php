<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\SwiftCart\Entities\TradedCommercialTransaction;
use Vaened\SwiftCart\Entities\TradedCommercialTransactionItems;

final class Invoice implements TradedCommercialTransaction
{
    public function __construct(
        private readonly InvoiceDetailItems $items,
    )
    {
    }

    public static function create(InvoiceDetailItems $items): self
    {
        return new self($items);
    }

    public function items(): TradedCommercialTransactionItems
    {
        return new TradedCommercialTransactionItems(
            $this->items->values()
        );
    }
}
