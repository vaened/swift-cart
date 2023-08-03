<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart\Entities;

interface DraftCommercialTransaction
{
    public function items(): CommercialTransactionItems;
}