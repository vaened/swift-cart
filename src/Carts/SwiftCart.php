<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\Support\Types\ArrayList;
use Vaened\SwiftCart\Entities\Identifiable;
use Vaened\SwiftCart\Items\CartItem;
use Vaened\SwiftCart\Items\CartItems;
use Vaened\SwiftCart\Summary;

abstract class SwiftCart
{
    abstract protected function staging(): CartItems;

    public function summary(): Summary
    {
        return $this->staging()->summary();
    }

    public function locate(Identifiable $identifiable): ?CartItem
    {
        return $this->staging()->locate($identifiable);
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
}
