<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Brick\Money\Money;
use Vaened\PriceEngine\TotalSummary;
use Vaened\Support\Types\ArrayObject;

final class TotalSummaries extends ArrayObject
{
    public static function of(array $summaries): self
    {
        return new self($summaries);
    }

    public function subtotal(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->subtotal()
        );
    }

    public function totalTaxes(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->taxes()->total()
        );
    }

    public function totalCharges(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->charges()->total()
        );
    }

    public function totalDiscounts(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->discounts()->total()
        );
    }

    public function total(): Money
    {
        return $this->sum(
            static fn(TotalSummary $summary) => $summary->total()
        );
    }

    public function summary(): Summary
    {
        return new Summary(
            subtotal      : $this->subtotal(),
            totalTaxes    : $this->totalTaxes(),
            totalCharges  : $this->totalCharges(),
            totalDiscounts: $this->totalDiscounts(),
            total         : $this->total(),
        );
    }

    protected function type(): string
    {
        return TotalSummary::class;
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
