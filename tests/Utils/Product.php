<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Entities\Chargeable;
use Vaened\SwiftCart\Entities\Discountable;
use Vaened\SwiftCart\Entities\Tradable;
use Vaened\SwiftCart\UniqueId;

use function Lambdish\Phunctional\each;

final class Product implements Tradable, Discountable, Chargeable
{
    private readonly Adjustments $charges;

    private readonly Adjustments $discounts;

    public function __construct(
        private readonly string $id,
        private readonly Amount $amount,
    )
    {
        $this->charges   = Adjustments::empty();
        $this->discounts = Adjustments::empty();
    }

    public static function random(): self
    {
        return new self(UniqueId::random()->uniqueId(), Amount::taxable(MoneyFactory::random()));
    }

    public static function create(Amount $amount): self
    {
        return new self(UniqueId::random()->uniqueId(), $amount);
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

    public function uniqueId(): string
    {
        return $this->id;
    }

    public function amount(): Amount
    {
        return $this->amount;
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
