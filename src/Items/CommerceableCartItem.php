<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Items;

use BackedEnum;
use UnitEnum;
use Vaened\PriceEngine\Adjustments\Charge;
use Vaened\PriceEngine\Adjustments\Discount;
use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\SwiftCart\Entities\Chargeable;
use Vaened\SwiftCart\Entities\Discountable;
use Vaened\SwiftCart\Entities\Tradable;

final class CommerceableCartItem extends CartItem
{
    private readonly bool $discountable;

    private readonly bool $chargeable;

    public function __construct(
        Tradable $quotable,
        int      $quantity,
        Taxes    $taxes = null,
    )
    {
        $this->discountable = $quotable instanceof Discountable;
        $this->chargeable   = $quotable instanceof Chargeable;

        parent::__construct($quotable, $quantity, $taxes ?? Taxes::empty());
    }

    public function isDiscountable(): bool
    {
        return $this->discountable;
    }

    public function isChargeable(): bool
    {
        return $this->chargeable;
    }

    public function update(int $quantity): void
    {
        $this->cashier()->update($quantity);
    }

    public function apply(Discount ...$discounts): void
    {
        if ($this->isDiscountable()) {
            $this->cashier()->apply(...$discounts);
        }
    }

    public function cancelDiscount(BackedEnum|UnitEnum|string $discountCode): void
    {
        $this->cashier()->cancelDiscount($discountCode);
    }

    public function add(Charge ...$charges): void
    {
        $this->cashier()->add(...$charges);
    }

    public function revertCharge(BackedEnum|UnitEnum|string $chargeCode): void
    {
        if ($this->isChargeable()) {
            $this->cashier()->revertCharge($chargeCode);
        }
    }
}
