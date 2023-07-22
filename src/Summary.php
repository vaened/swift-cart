<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Brick\Money\Money;

final class Summary
{
    public function __construct(
        private readonly Money $subtotal,
        private readonly Money $totalTaxes,
        private readonly Money $totalCharges,
        private readonly Money $totalDiscounts,
        private readonly Money $total,
    )
    {
    }

    public function subtotal(): Money
    {
        return $this->subtotal;
    }

    public function totalTaxes(): Money
    {
        return $this->totalTaxes;
    }

    public function totalCharges(): Money
    {
        return $this->totalCharges;
    }

    public function totalDiscounts(): Money
    {
        return $this->totalDiscounts;
    }

    public function total(): Money
    {
        return $this->total;
    }
}
