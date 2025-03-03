<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\SwiftCart\Entities\CommercialTransactionItems;
use Vaened\SwiftCart\Entities\RegisteredCommercialTransaction;

use function Lambdish\Phunctional\each;

final class Invoice implements RegisteredCommercialTransaction
{
    private readonly Adjustments $charges;

    private readonly Adjustments $discounts;

    public function __construct(
        private readonly InvoiceDetailItems $items,
    )
    {
        $this->charges   = Adjustments::empty();
        $this->discounts = Adjustments::empty();
    }

    public static function create(InvoiceDetailItems $items): self
    {
        return new self($items);
    }

    public function with(Discount|Charge ...$adjusters): self
    {
        each(function (Discount|Charge $adjuster) {
            if ($adjuster instanceof Discount) {
                $this->discounts->push($adjuster);
            } else {
                $this->charges->push($adjuster);
            }
        }, $adjusters);

        return $this;
    }

    public function items(): CommercialTransactionItems
    {
        return $this->items->toCommercialItems();
    }

    public function charges(): Adjustments
    {
        return $this->charges;
    }

    public function discounts(): Adjustments
    {
        return $this->discounts;
    }
}
