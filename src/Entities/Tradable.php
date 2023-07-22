<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

use Vaened\PriceEngine\Money\Amount;

interface Tradable extends Identifiable
{
    public function amount(): Amount;
}