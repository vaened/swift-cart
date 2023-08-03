<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Vaened\SwiftCart\Providers\SimpleCashierProvider;
use Vaened\SwiftCart\SwiftCartConfig;
use Vaened\SwiftCart\Tests\Utils\MoneyFactory;
use Vaened\SwiftCart\Tests\Utils\Products;

abstract class TestCase extends PhpUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Products::clean();
        $this->setUpConfig();
    }

    private function setUpConfig(): void
    {
        SwiftCartConfig::setDefaultCurrency(MoneyFactory::defaultCurrency());
        SwiftCartConfig::setDefaultContext(MoneyFactory::defaultContext());
        SwiftCartConfig::setCashierProvider(new SimpleCashierProvider());
    }
}

