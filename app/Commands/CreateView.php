<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateView extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'make:view';
    protected $description = 'Creates a new view in the specified directory';

    public function run(array $params)
    {
        $viewName = array_pop($params);
        $directory = array_pop($params);

        if (empty($viewName)) {
            CLI::error('You must provide a view name.');
            return;
        }

        // Default to app/Views if no directory is specified
        if (empty($directory)) {
            $directory = '';
        }

        // Ensure the directory is relative to the app/Views directory
        $baseDir = APPPATH . 'Views/';
        $fullDir = $baseDir . $directory;

        $filePath = $fullDir . '/' . $viewName . '.php';

        // Ensure the directory exists
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $template = <<<EOD
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>$viewName</title>
            </head>
            <body>
                <h1>$viewName</h1>
                <p>This is the $viewName view.</p>
            </body>
            </html>
        EOD;

        if (file_put_contents($filePath, $template) !== false) {
            CLI::write("View created successfully: $filePath", 'green');
        } else {
            CLI::error("Failed to create view: $filePath");
        }
    }
}