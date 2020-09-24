<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Command\CommandInterface;
use App\Github\Rate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CommandSubscriber implements EventSubscriberInterface
{
    private Rate $rate;

    public function __construct(Rate $rate)
    {
        $this->rate = $rate;
    }

    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => ['onCommand'],
            ConsoleEvents::TERMINATE => ['onTerminate'],
        ];
    }

    public function onCommand(ConsoleCommandEvent $event)
    {
        $this->logApiRate($event->getCommand(), new SymfonyStyle($event->getInput(), $event->getOutput()));
    }

    public function onTerminate(ConsoleTerminateEvent $event)
    {
        $this->logApiRate($event->getCommand(), new SymfonyStyle($event->getInput(), $event->getOutput()));
    }

    private function logApiRate(Command $command, SymfonyStyle $io)
    {
        if(!$command instanceof CommandInterface) {
            return;
        }

        $io->note(sprintf('Remaining API rate: %d', $this->rate->getRemaining()));
    }
}