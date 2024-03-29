<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\MissingReplacement\ForGroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\MissingReplacement\ForMatchMessage;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

class StrategyFallbackAdapter implements GroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var ReplaceSubstitute */
    private $substitute;
    /** @var string */
    private $subject;

    public function __construct(GroupMapper $mapper, ReplaceSubstitute $substitute, string $subject)
    {
        $this->mapper = $mapper;
        $this->substitute = $substitute;
        $this->subject = $subject;
    }

    public function map(string $occurrence): ?string
    {
        $result = $this->mapper->map($occurrence);
        if ($result === null) {
            return $this->substitute->substitute($this->subject);
        }
        return $result;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
        if ($nameOrIndex === 0) {
            $this->substitute->useExceptionMessage(new ForMatchMessage($occurrence));
        } else {
            $this->substitute->useExceptionMessage(new ForGroupMessage($match, $nameOrIndex, $occurrence));
        }
        $this->mapper->useExceptionValues($occurrence, $nameOrIndex, $match);
    }
}
