<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Brick\Money\Money;

final class Summary
{
    private readonly Money $subtotal;

    private readonly Money $totalTaxes;

    private readonly Money $totalCharges;

    private readonly Money $totalDiscounts;

    private readonly Money $total;

    public function __construct(
        Money $subtotal = null,
        Money $totalTaxes = null,
        Money $totalCharges = null,
        Money $totalDiscounts = null,
        Money $total = null,
    )
    {
        $this->subtotal       = $subtotal ?? MoneyFactory::zero();
        $this->totalTaxes     = $totalTaxes ?? MoneyFactory::zero();
        $this->totalCharges   = $totalCharges ?? MoneyFactory::zero();
        $this->totalDiscounts = $totalDiscounts ?? MoneyFactory::zero();
        $this->total          = $total ?? MoneyFactory::zero();
    }

    public static function zero(): self
    {
        return new self();
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
