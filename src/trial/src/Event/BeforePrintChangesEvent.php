<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class BeforePrintChangesEvent extends Event
{
    public const NAME = 'before.print_changes';

    protected array $changes;

    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function setChanges(array $changes): void
    {
        $this->changes = $changes;
    }
}