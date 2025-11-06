<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ContainerCommandLoader as BaseContainerCommandLoader;

class ContainerCommandLoader extends BaseContainerCommandLoader
{
    /**
     * Resolve a command from the container.
     *
     * @param  string  $name
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     */
    public function get(string $name): \Symfony\Component\Console\Command\Command
    {
        $command = parent::get($name);

        // Set the Laravel application instance on the command
        if ($command instanceof Command) {
            $command->setLaravel($this->container);
        }

        return $command;
    }
}

