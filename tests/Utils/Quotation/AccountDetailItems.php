<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Quotation;

use Vaened\SwiftCart\Tests\Utils\CommercialTransactionDetailItems;

final class AccountDetailItems extends CommercialTransactionDetailItems
{
    public static function type(): string
    {
        return AccountDetailItem::class;
    }
}
