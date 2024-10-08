<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\SwiftCart\Tests\Utils\CommercialTransactionDetailItems;

final class InvoiceDetailItems extends CommercialTransactionDetailItems
{
    public static function type(): string
    {
        return InvoiceDetailItem::class;
    }
}
