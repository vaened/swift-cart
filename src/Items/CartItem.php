<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\PriceEngine\Adjusters\Adjusters;
use Vaened\PriceEngine\Adjusters\Tax\Taxes;
use Vaened\PriceEngine\Calculators\StandardCashier;
use Vaened\PriceEngine\Cashier;
use Vaened\PriceEngine\Money\Amount;
use Vaened\PriceEngine\TotalSummary;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Entities\Tradable;

abstract class CartItem implements Identifiable
{
    private readonly Cashier $cashier;

    public function __construct(
        protected readonly Tradable $tradable,
        int                         $quantity,
        Taxes                       $taxes = new Taxes([]),
        Adjusters                   $charges = new Adjusters([]),
        Adjusters                   $discounts = new Adjusters([]),
    )
    {
        $this->cashier = $this->createCashier($tradable->amount(), $quantity, $taxes, $charges, $discounts);
    }

    public function uniqueId(): string
    {
        return $this->tradable->uniqueId();
    }

    public function quantity(): int
    {
        return $this->cashier->quantity();
    }

    public function summary(): TotalSummary
    {
        return $this->cashier;
    }

    public function tradable(): Tradable
    {
        return $this->tradable;
    }

    protected function cashier(): Cashier
    {
        return $this->cashier;
    }

    protected function createCashier(
        Amount    $amount,
        int       $quantity,
        Taxes     $taxes,
        Adjusters $charges,
        Adjusters $discounts
    ): Cashier
    {
        return new StandardCashier($amount, $quantity, $taxes, $charges, $discounts);
    }
}
