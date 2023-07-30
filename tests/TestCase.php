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

abstract class TestCase extends PhpUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpConfig();
    }

    private function setUpConfig(): void
    {
        SwiftCartConfig::setDefaultCurrency(MoneyFactory::defaultCurrency());
        SwiftCartConfig::setDefaultContext(MoneyFactory::defaultContext());
        SwiftCartConfig::setCashierProvider(new SimpleCashierProvider());
    }
}

