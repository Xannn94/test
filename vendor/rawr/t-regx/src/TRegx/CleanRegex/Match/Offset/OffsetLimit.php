<?php
namespace TRegx\CleanRegex\Match\Offset;

use TRegx\CleanRegex\Internal\PatternLimit;

interface OffsetLimit extends PatternLimit
{
    /**
     * @return (int|null)[]
     */
    public function all(): array;

    public function first();

    /**
     * @param int $limit
     * @return array (int|null)[]
     */
    public function only(int $limit): array;
}
