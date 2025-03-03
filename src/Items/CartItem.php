<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\PriceEngine\Cashier;
use Vaened\PriceEngine\Money\Amount;
use Vaened\PriceEngine\Summary;
use Vaened\SwiftCart\Attributes;
use Vaened\SwiftCart\Concerns\HasAttributes;
use Vaened\SwiftCart\Entities\{Attributable, Chargeable, Discountable, Identifiable, Tradable};
use Vaened\SwiftCart\SwiftCartConfig;

abstract class CartItem implements Identifiable
{
    use HasAttributes;

    private readonly Cashier $cashier;

    public function __construct(
        protected readonly Tradable $tradable,
        int                         $quantity,
        Taxes                       $taxes = new Taxes([]),
    )
    {
        $this->setAttributes($tradable instanceof Attributable ? $tradable->attributes() : Attributes::empty());
        $this->cashier = $this->createCashier(
            $tradable->amount(),
            $quantity,
            $taxes,
            $tradable instanceof Chargeable ? $tradable->charges() : Adjustments::empty(),
            $tradable instanceof Discountable ? $tradable->discounts() : Adjustments::empty()
        );
    }

    public function uniqueId(): string
    {
        return $this->tradable->uniqueId();
    }

    public function quantity(): int
    {
        return $this->cashier->quantity();
    }

    public function summary(): Summary
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
        Adjustments $charges,
        Adjustments $discounts
    ): Cashier
    {
        return SwiftCartConfig::provider()->createCashier($amount, $quantity, $taxes, $charges, $discounts);
    }
}
