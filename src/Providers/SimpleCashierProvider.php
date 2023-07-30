<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Providers;

use Vaened\PriceEngine\Adjustments\Adjusters;
use Vaened\PriceEngine\Adjustments\Tax\Taxes;
use Vaened\PriceEngine\Cashier;
use Vaened\PriceEngine\Cashiers\SimpleCashier;
use Vaened\PriceEngine\Money\Amount;
use Vaened\SwiftCart\CashierProvider;

final class SimpleCashierProvider implements CashierProvider
{
    public function createCashier(
        Amount    $amount,
        int       $quantity,
        Taxes     $taxes,
        Adjusters $charges,
        Adjusters $discounts,
    ): Cashier
    {
        return new SimpleCashier($amount, $quantity, $taxes, $charges, $discounts);
    }
}
