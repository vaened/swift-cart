<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Utils;

use Brick\Money\Context;
use Brick\Money\Context\CustomContext;
use Brick\Money\Currency;
use Brick\Money\Money as BrickMoney;

use function rand;

final class MoneyFactory
{
    public static function random(): BrickMoney
    {
        return BrickMoney::of(rand(2, 99), self::defaultCurrency(), self::defaultContext());
    }

    public static function of(float $amount): BrickMoney
    {
        return BrickMoney::of($amount, self::defaultCurrency(), self::defaultContext());
    }

    public static function zero(): BrickMoney
    {
        return BrickMoney::zero(self::defaultCurrency(), self::defaultContext());
    }

    public static function defaultCurrency(): Currency
    {
        return Currency::of('USD');
    }

    public static function defaultContext(): Context
    {
        return new CustomContext(4);
    }
}
