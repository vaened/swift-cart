<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\SwiftCart\Tests\Utils\Billing\Invoice;
use Vaened\SwiftCart\Tests\Utils\Billing\InvoiceDetailItem;
use Vaened\SwiftCart\Tests\Utils\Billing\InvoiceDetailItems;
use Vaened\SwiftCart\Tests\Utils\Quotation\Account;
use Vaened\SwiftCart\Tests\Utils\Quotation\AccountDetailItem;
use Vaened\SwiftCart\Tests\Utils\Quotation\AccountDetailItems;

use function Lambdish\Phunctional\map;

final class Billier
{
    public static function invoice(array $detail): Invoice
    {
        return Invoice::create(
            InvoiceDetailItems::from(
                map(self::toDetailItemOf(InvoiceDetailItem::class), $detail)
            )
        );
    }

    public static function account(array $detail): Account
    {
        return Account::create(
            AccountDetailItems::from(
                map(self::toDetailItemOf(AccountDetailItem::class), $detail)
            )
        );
    }

    private static function toDetailItemOf(string $detail): callable
    {
        return static fn(Product|CommercialTransactionDetailItem $item): CommercialTransactionDetailItem => $item instanceof $detail
            ? $item
            : call_user_func_array([$detail, 'from'], [$item]);
    }
}
