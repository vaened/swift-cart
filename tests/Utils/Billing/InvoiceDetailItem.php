<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils\Billing;

use Vaened\PriceEngine\Adjusters\Adjusters;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\Entities\Traded;
use Vaened\SwiftCart\Tests\Utils\Product;

final class InvoiceDetailItem implements Traded
{
    public function __construct(
        private readonly Product   $product,
        private readonly Amount    $amount,
        private readonly int       $quantity,
        private readonly Adjusters $charges,
        private readonly Adjusters $discounts,

    )
    {
    }

    public static function from(Product $product): self
    {
        return new self($product, $product->amount(), 1, Adjusters::from([]), Adjusters::from([]));
    }

    public static function create(
        Product   $product,
        Amount    $amount,
        int       $quantity = 1,
        Adjusters $charges = null,
        Adjusters $discounts = null,
    ): self
    {
        return new self($product, $amount, $quantity, $charges ?? Adjusters::from([]), $discounts ?? Adjusters::from([]));
    }

    public function uniqueId(): string
    {
        return $this->product->uniqueId();
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function description(): string
    {
        return $this->product->description();
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function discounts(): Adjusters
    {
        return $this->discounts;
    }

    public function charges(): Adjusters
    {
        return $this->charges;
    }
}
