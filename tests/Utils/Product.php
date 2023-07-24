<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Entities\Tradable;
use Vaened\SwiftCart\UniqueId;

final class Product implements Tradable
{
    public function __construct(
        private readonly string $id,
        private readonly Amount $amount,
    )
    {
    }

    public static function random(): self
    {
        return new self(UniqueId::random()->uniqueId(), Amount::taxable(MoneyFactory::random()));
    }

    public static function create(Amount $amount): self
    {
        return new self(UniqueId::random()->uniqueId(), $amount);
    }

    public function uniqueId(): string
    {
        return $this->id;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }
}
