<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Vaened\PriceEngine\Adjustments\AdjustmentScheme;
use Vaened\PriceEngine\Modifier;
use Vaened\PriceEngine\Modifiers;
use Vaened\SwiftCart\Providers\SimpleCashierProvider;
use Vaened\SwiftCart\SwiftCartConfig;
use Vaened\SwiftCart\Tests\Utils\Carts;
use Vaened\SwiftCart\Tests\Utils\MoneyFactory;
use Vaened\SwiftCart\Tests\Utils\Products;

abstract class TestCase extends PhpUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Products::clean();
        Carts::clean();
        $this->setUpConfig();
    }

    private function setUpConfig(): void
    {
        SwiftCartConfig::setDefaultCurrency(MoneyFactory::defaultCurrency());
        SwiftCartConfig::setDefaultContext(MoneyFactory::defaultContext());
        SwiftCartConfig::setCashierProvider(new SimpleCashierProvider());
    }

    protected static function collect(array $adjustments): Modifiers
    {
        return new Modifiers(
            $adjustments,
            SwiftCartConfig::defaultCurrency(),
            SwiftCartConfig::defaultContext(),
        );
    }

    protected static function createAdjustment(float $amount, AdjustmentScheme $scheme): Modifier
    {
        return new Modifier(MoneyFactory::of($amount), $scheme->type(), $scheme->mode(), $scheme->value(), $scheme->code());
    }
}

