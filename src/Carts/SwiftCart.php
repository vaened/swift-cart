<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\PriceEngine\AdjustmentManager;
use Vaened\PriceEngine\Adjustments;
use Vaened\PriceEngine\Adjustments\Adjusters;
use Vaened\Support\Types\ArrayList;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Items\{CartItem, CartItems};
use Vaened\SwiftCart\Summary;
use Vaened\SwiftCart\Totalizer;

abstract class SwiftCart
{
    abstract public function locate(Identifiable $identifiable): ?CartItem;

    abstract protected function staging(): CartItems;

    abstract protected function globalChargesManager(): AdjustmentManager;

    abstract protected function globalDiscountsManager(): AdjustmentManager;

    public function globalCharges(): Adjustments
    {
        return $this->syncAdjustments($this->globalChargesManager());
    }

    public function globalDiscounts(): Adjustments
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

    protected function createManagerOf(Adjusters $adjusters): AdjustmentManager
    {
        return new AdjustmentManager($adjusters, $this->totalizer()->total(), quantity: 1);
    }

    protected function totalizer(): Totalizer
    {
        return $this->staging()->totalizer();
    }

    private function syncAdjustments(AdjustmentManager $manager): Adjustments
    {
        $manager->revalue($this->totalizer()->total());
        return $manager->adjustments();
    }
}
