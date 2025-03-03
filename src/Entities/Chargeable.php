<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

use Vaened\PriceEngine\Adjustments\Adjustments;

interface Chargeable
{
    public function charges(): Adjustments;
}