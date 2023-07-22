<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

use Vaened\PriceEngine\Adjusters\Adjusters;

interface Traded extends Tradable
{
    public function quantity(): int;

    public function discounts(): Adjusters;

    public function charges(): Adjusters;
}