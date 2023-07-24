<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

interface Traded extends Tradable, Discountable, Chargeable
{
    public function quantity(): int;
}