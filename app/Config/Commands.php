<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    public $commands = [
        'make:controller' => \App\Commands\CreateController::class,
        'make:view' => \App\Commands\CreateView::class,
    ];
}