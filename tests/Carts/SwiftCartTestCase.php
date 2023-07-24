<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Tests\Carts;

use Vaened\SwiftCart\Carts\SwiftCart;
use Vaened\SwiftCart\Tests\TestCase;

abstract class SwiftCartTestCase extends TestCase
{
    abstract protected function cart(): SwiftCart;
}
