<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Vaened\PriceEngine\Adjustment;
use Vaened\PriceEngine\Adjustments;
use Vaened\PriceEngine\Adjustments\AdjusterScheme;
use Vaened\SwiftCart\Providers\SimpleCashierProvider;
use Vaened\SwiftCart\SwiftCartConfig;
use Vaened\SwiftCart\Tests\Utils\Carts;
use Vaened\SwiftCart\Tests\Utils\MoneyFactory;
use Vaened\SwiftCart\Tests\Utils\Products;

abstract class TestCase extends PhpUnitTestCase
{
    protected static function collect(array $adjustments): Adjustments
    {
        return new Adjustments(
            $adjustments,
            SwiftCartConfig::defaultCurrency(),
            SwiftCartConfig::defaultContext(),
        );
    }

    protected static function createAdjustment(float $amount, AdjusterScheme $scheme): Adjustment
    {
        return new Adjustment(MoneyFactory::of($amount), $scheme->type(), $scheme->mode(), $scheme->value(), $scheme->code());
    }

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
}

