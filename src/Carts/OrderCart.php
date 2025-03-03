<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Carts;

use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\SwiftCart\Entities\{DraftCommercialTransaction, Identifiable};
use Vaened\SwiftCart\Items\{CommerceableCartItem, ImmutableCartItem, ImmutableCartItems};
use Vaened\SwiftCart\NotFoundItem;

/**
 * Shopping cart for orders
 *
 * This class represents a shopping cart that loads data from previous records.
 * Generally used to load items from an account or flat quote, without discounts
 * or charges or an unfinished purchase etc.
 */
final class OrderCart extends ShoppingCart
{
    private readonly ImmutableCartItems $immutables;

    public function __construct(DraftCommercialTransaction $transaction, Taxes $taxes = new Taxes([]))
    {
        parent::__construct($taxes);
        $this->immutables = $transaction->items()->toImmutables();
    }

    public function pull(Identifiable $identifiable): CommerceableCartItem
    {
        $immutableItem = $this->immutables->locate($identifiable);
        $this->ensureValidItem($immutableItem);

        $commerceableItem = $immutableItem->toCommerceable();
        $this->attach($commerceableItem);

        return $commerceableItem;
    }

    public function pullAll(): void
    {
        $this->immutables->except($this->staging())
                         ->toCommerceables()
                         ->each($this->attachAll());
    }

    private function attachAll(): callable
    {
        return fn(CommerceableCartItem $commerceable) => $this->attach($commerceable);
    }

    private function ensureValidItem(?ImmutableCartItem $item): void
    {
        if (null === $item) {
            throw new NotFoundItem();
        }
    }
}
