<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Providers;

use Vaened\PriceEngine\Adjustments\Adjustments;
use Vaened\PriceEngine\Adjustments\Taxation\Taxes;
use Vaened\PriceEngine\Cashier;
use Vaened\PriceEngine\Cashiers\RegularCashier;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\CashierProvider;

final class RegularCashierProvider implements CashierProvider
{
    public function createCashier(
        Amount      $amount,
        int         $quantity,
        Taxes       $taxes,
        Adjustments $charges,
        Adjustments $discounts,
    ): Cashier
    {
        return new RegularCashier($amount, $quantity, $taxes, $charges, $discounts);
    }
}
