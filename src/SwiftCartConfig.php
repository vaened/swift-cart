<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Brick\Money\Context;
use Brick\Money\Context\DefaultContext;
use Brick\Money\Currency;
use Vaened\PriceEngine\PriceEngineConfig;
use Vaened\SwiftCart\Providers\SimpleCashierProvider;

final class SwiftCartConfig
{
    private static Currency        $currency;

    private static Context         $context;

    private static CashierProvider $provider;

    public static function defaultCurrency(): Currency
    {
        return self::$currency ??= Currency::of('USD');
    }

    public static function setDefaultCurrency(Currency $currency): void
    {
        self::$currency = $currency;
    }

    public static function defaultContext(): Context
    {
        return self::$context ??= new DefaultContext();
    }

    public static function setDefaultContext(Context $context): void
    {
        self::$context = $context;
    }

    public static function defaultRoundingMode(): int
    {
        return PriceEngineConfig::defaultRoundingMode();
    }

    public static function setDefaultRoundingMode(int $roundingMode): void
    {
        PriceEngineConfig::setDefaultRoundingMode($roundingMode);
    }

    public static function provider(): CashierProvider
    {
        return self::$provider ??= new SimpleCashierProvider();
    }

    public static function setCashierProvider(CashierProvider $provider): void
    {
        self::$provider = $provider;
    }
}
