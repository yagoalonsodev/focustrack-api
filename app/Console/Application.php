<?php

namespace App\Console;

use Illuminate\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Set the container command loader for lazy resolution.
     *
     * @return $this
     */
    public function setContainerCommandLoader()
    {
        $this->setCommandLoader(new ContainerCommandLoader($this->laravel, $this->commandMap));

        return $this;
    }
}

