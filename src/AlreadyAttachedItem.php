<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Throwable;
use Vaened\SwiftCart\Entities\Tradable;

use function sprintf;

final class AlreadyAttachedItem extends SwiftCartException
{
    public function __construct(Tradable $quotable, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('the <%s> element is already loaded in the list', $quotable->uniqueId()),
            $code,
            $previous);
    }

    public static function is(Tradable $tradable): self
    {
        return new self($tradable);
    }
}
