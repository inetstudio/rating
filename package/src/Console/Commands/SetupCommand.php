<?php

namespace InetStudio\RatingsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:ratings-package:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup ratings package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Ratings setup',
                'command' => 'inetstudio:ratings-package:ratings:setup',
            ],
        ];
    }
}
