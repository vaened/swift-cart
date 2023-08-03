<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\PriceEngine\Adjustments\Adjusters;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\SwiftCart\Entities\TradedCommercialTransaction;
use Vaened\SwiftCart\Entities\CommercialTransactionItems;

use function Lambdish\Phunctional\each;

final class Invoice implements TradedCommercialTransaction
{
    private readonly Adjusters $charges;

    private readonly Adjusters $discounts;

    public function __construct(
        private readonly InvoiceDetailItems $items,
    )
    {
        $this->charges   = Adjusters::empty();
        $this->discounts = Adjusters::empty();
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
        return new CommercialTransactionItems(
            $this->items->values()
        );
    }

    public function charges(): Adjusters
    {
        return $this->charges;
    }

    public function discounts(): Adjusters
    {
        return $this->discounts;
    }
}
