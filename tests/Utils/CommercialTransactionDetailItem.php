<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Entities\CommercialTransactionItem;

abstract class CommercialTransactionDetailItem implements CommercialTransactionItem
{
    public function __construct(
        private readonly Product     $product,
        private readonly Amount      $amount,
        private readonly int         $quantity,
        private readonly Adjustments $charges,
        private readonly Adjustments $discounts,

    )
    {
    }

    public static function from(Product $product): static
    {
        return new static($product, $product->amount(), 1, Adjustments::empty(), Adjustments::empty());
    }

    public static function create(
        Product     $product,
        Amount      $amount,
        int         $quantity = 1,
        Adjustments $charges = null,
        Adjustments $discounts = null,
    ): static
    {
        return new static($product, $amount, $quantity, $charges ?? Adjustments::empty(), $discounts ?? Adjustments::empty());
    }

    public function uniqueId(): string
    {
        return $this->product->uniqueId();
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function discounts(): Adjustments
    {
        return $this->discounts;
    }

    public function charges(): Adjustments
    {
        return $this->charges;
    }
}
