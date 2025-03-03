<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Brick\Money\Money;
use Vaened\PriceEngine\AdjustmentManager;
use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Modifiers;
use Vaened\Support\Types\ArrayList;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Items\{CartItem, CartItems};
use Vaened\SwiftCart\Summary;
use Vaened\SwiftCart\SwiftCartConfig;
use Vaened\SwiftCart\Totalizer;

abstract class SwiftCart
{
    abstract public function locate(Identifiable $identifiable): ?CartItem;

    abstract protected function staging(): CartItems;

    abstract protected function globalChargesManager(): AdjustmentManager;

    abstract protected function globalDiscountsManager(): AdjustmentManager;

    public function globalCharges(): Modifiers
    {
        return $this->syncAdjustments($this->globalChargesManager());
    }

    public function globalDiscounts(): Modifiers
    {
        return $this->syncAdjustments($this->globalDiscountsManager());
    }

    public function summary(): Summary
    {
        return $this->totalizer()->summary(
            additionalCharges  : $this->globalCharges()->total(),
            additionalDiscounts: $this->globalDiscounts()->total(),
        );
    }

    public function has(Identifiable $identifiable): bool
    {
        return $this->staging()->has($identifiable);
    }

    public function remove(Identifiable $identifiable): void
    {
        $this->staging()->remove($identifiable);
    }

    public function items(): ArrayList
    {
        return new ArrayList(
            $this->staging()->items()
        );
    }

    protected function createManagerOf(Adjustments $adjustments): AdjustmentManager
    {
        return new AdjustmentManager(
            $adjustments,
            Money::zero(SwiftCartConfig::defaultCurrency(), SwiftCartConfig::defaultContext()),
            quantity: 1
        );
    }

    protected function totalizer(): Totalizer
    {
        return $this->staging()->totalizer();
    }

    private function syncAdjustments(AdjustmentManager $manager): Modifiers
    {
        if (
            !$manager->modifiers()->isEmpty() &&
            !$this->staging()->isEmpty()
        ) {
            $manager->revalue($this->totalizer()->total());
        }

        return $manager->modifiers();
    }
}
