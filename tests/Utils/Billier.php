<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\SwiftCart\Tests\Utils\Billing\Invoice;
use Vaened\SwiftCart\Tests\Utils\Billing\InvoiceDetailItem;
use Vaened\SwiftCart\Tests\Utils\Billing\InvoiceDetailItems;

use function Lambdish\Phunctional\map;

final class Billier
{
    public static function invoice(array $detail): Invoice
    {
        return Invoice::create(
            InvoiceDetailItems::from(
                map(self::toDetailItem(), $detail)
            )
        );
    }

    private static function toDetailItem(): callable
    {
        return static fn(Product|InvoiceDetailItem $item): InvoiceDetailItem => $item instanceof InvoiceDetailItem
            ? $item
            : InvoiceDetailItem::from($item);
    }
}
