<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

interface TradedCommercialTransaction extends Discountable, Chargeable
{
    public function items(): TradedCommercialTransactionItems;
}