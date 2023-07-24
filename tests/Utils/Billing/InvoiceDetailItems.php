<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\Support\Types\ArrayObject;

final class InvoiceDetailItems extends ArrayObject
{
    public static function from(array $items): self
    {
        return new self($items);
    }

    protected function type(): string
    {
        return InvoiceDetailItem::class;
    }
}
