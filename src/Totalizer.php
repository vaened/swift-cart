<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Brick\Money\Money;
use Vaened\PriceEngine\TotalSummary;
use Vaened\Support\Types\ArrayObject;

final class Totalizer extends ArrayObject
{
    public static function of(array $summaries): self
    {
        return new self($summaries);
    }

    public function summary(Money $additionalCharges = null, Money $additionalDiscounts = null): Summary
    {
        $subtotal = $this->subtotal();

        return new Summary(
            subtotal      : $subtotal,
            totalTaxes    : $this->totalTaxes(),
            totalCharges  : $this->totalCharges()
                                 ->plus(null === $additionalCharges ? 0 : $additionalCharges),
            totalDiscounts: $this->totalDiscounts()
                                 ->plus(null === $additionalDiscounts ? 0 : $additionalDiscounts),
            total         : $this->total(),
        );
    }

    public function total(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->total()
        );
    }

    protected function type(): string
    {
        return TotalSummary::class;
    }

    private function subtotal(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->subtotal()->gross()
        );
    }

    private function totalTaxes(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->taxes()->total()
        );
    }

    private function totalCharges(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->charges()->total()
        );
    }

    private function totalDiscounts(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->discounts()->total()
        );
    }

    private function sum(callable $callback): Money
    {
        return $this->reduce(
            static fn(?Money $money, TotalSummary $summary) => null === $money
                ? $callback($summary)
                : $money->plus($callback($summary))
        ) ?? $this->zero();
    }

    private function zero(): Money
    {
        return Money::zero(SwiftCartConfig::defaultCurrency(), SwiftCartConfig::defaultContext());
    }
}
