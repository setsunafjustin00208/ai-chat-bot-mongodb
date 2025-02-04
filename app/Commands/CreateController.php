<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateController extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'make:controller';
    protected $description = 'Creates a new controller in the specified directory';

    public function run(array $params)
    {
        $controllerName = array_pop($params);
        $directory = array_pop($params);

        if (empty($controllerName)) {
            CLI::error('You must provide a controller name.');
            return;
        }

        // Default to app/Controllers if no directory is specified
        if (empty($directory)) {
            $directory = '';
        }

        // Ensure the directory is relative to the app/Controllers directory
        $baseDir = APPPATH . 'Controllers/';
        $fullDir = $baseDir . $directory;

        $filePath = $fullDir . '/' . $controllerName . '.php';

        // Ensure the directory exists
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $template = <<<EOD
<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class $controllerName extends Controller
{
    public function index()
    {
        return view('welcome_message');
    }
}
EOD;

        if (file_put_contents($filePath, $template) !== false) {
            CLI::write("Controller created successfully: $filePath", 'green');
        } else {
            CLI::error("Failed to create controller: $filePath");
        }
    }
}