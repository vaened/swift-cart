<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\PriceEngine\Cashier;
use Vaened\PriceEngine\Money\Amount;

interface CashierProvider
{
    public function createCashier(
        Amount    $amount,
        int       $quantity,
        Taxes     $taxes,
        Adjustments $charges,
        Adjustments $discounts,
    ): Cashier;
}