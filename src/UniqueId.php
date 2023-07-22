<?php
/**
 * @author enea dhack <enea.so@live.com>
 */

declare(strict_types=1);

namespace Vaened\SwiftCart;

use Vaened\SwiftCart\Entities\Identifiable;

use function rand;

final class UniqueId implements Identifiable
{
    public function __construct(
        private readonly string $id,
    )
    {
    }

    public static function random(): self
    {
        return new self((string)rand());
    }

    public function uniqueId(): string
    {
        return $this->id;
    }
}
