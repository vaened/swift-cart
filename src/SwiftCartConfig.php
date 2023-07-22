<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Brick\Math\RoundingMode;
use Brick\Money\Context;
use Brick\Money\Context\DefaultContext;
use Brick\Money\Currency;
use Vaened\PriceEngine\Calculator;

final class SwiftCartConfig
{
    private static Currency   $currency;

    private static Context    $context;

    private static Calculator $calculator;

    private static int        $roundingMode = RoundingMode::HALF_UP;

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

    public static function calculator(): Calculator
    {
        return self::$calculator ??= new Calculator();
    }

    public static function setCalculator(Calculator $calculator): void
    {
        self::$calculator = $calculator;
    }

    public static function defaultRoundingMode(): int
    {
        return self::$roundingMode;
    }

    public static function setDefaultRoundingMode(int $roundingMode): void
    {
        self::$roundingMode = $roundingMode;
    }
}
