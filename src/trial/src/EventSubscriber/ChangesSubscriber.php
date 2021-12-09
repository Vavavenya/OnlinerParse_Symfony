<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\BeforePrintChangesEvent;

class ChangesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'before.print_changes' => 'setEmptyChanges'
        ];
    }

    public function setEmptyChanges(BeforePrintChangesEvent $event)
    {
        $event->setChanges([['clown','clown','clown']]);
    }
}