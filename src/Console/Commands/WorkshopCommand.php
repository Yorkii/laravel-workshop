<?php namespace Yorki\Workshop\Console\Commands;

use \Illuminate\Console\Command;
use \Illuminate\Filesystem\Filesystem;

class WorkshopCommand extends Command
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * @param string $filePath
     */
    public function addRoutesFromFile($filePath)
    {
        $web = $this->files->get(base_path('routes/web.php'));
        $adminRoutesPos = mb_strpos($web, '[@Admin-Routes@]');

        if ($adminRoutesPos !== false) {
            $tmp = mb_substr($web, 0, $adminRoutesPos);
            $adminRoutesPos = mb_strrpos($tmp, '/');
            $tmp = mb_substr($web, 0, $adminRoutesPos);
            $tmp .= $this->files->get($filePath) . PHP_EOL . PHP_EOL;
            $tmp .= mb_substr($web, $adminRoutesPos);

            $this->files->put(base_path('routes/web.php'), $tmp);
        }
    }

    /**
     * @param string $model
     * @param string $managerContractClass
     * @param string $managerClass
     */
    public function addManagerResolver($model, $managerContractClass, $managerClass)
    {
        $this->addResolver($model . ' manager resolver', $managerContractClass, $managerClass);
    }

    /**
     * @param string $title
     * @param string $contractClass
     * @param string $class
     */
    protected function addResolver($title, $contractClass, $class)
    {
        $bind = '//' . $title . PHP_EOL;
        $bind .= '$app->bind(' . $contractClass . ', ' . $class . ');';
        $appPhp = $this->files->get(base_path('bootstrap/app.php'));
        $appPhp = str_replace('return $app;', $bind . PHP_EOL . PHP_EOL . 'return $app;', $appPhp);

        $this->files->put(
            base_path('bootstrap/app.php'),
            $appPhp
        );
    }

    /**
     * @param string $namespace
     * @param array $views
     */
    public function copyAdminViews($namespace, array $views)
    {
        $namespacePath = base_path('resources/views/admin/' . $namespace);

        if (!file_exists($namespacePath)) {
            $this->files->makeDirectory($namespacePath, 0755, true);
        }

        foreach ($views as $view) {
            $this->files->copy(__DIR__ . '/Stubs/views/admin/' . $namespace . '/' . $view . '.blade.php', base_path('resources/views/admin/' . $namespace . '/' . $view . '.blade.php'));
        }
    }

    /**
     * @param string $className
     */
    public function copyManager($className)
    {
        if (class_exists('\App\Managers\\' . $className)) {
            return;
        }

        if (!file_exists(base_path('app/Managers/Contracts'))) {
            $this->files->makeDirectory(base_path('app/Managers/Contracts'), 0755, true);
        }

        $this->files->copy(__DIR__ . '/Stubs/Managers/Contracts/' . $className . 'Contract.php', base_path('app/Managers/Contracts/' . $className . 'Contract.php'));
        $this->files->copy(__DIR__ . '/Stubs/Managers/' . $className . '.php', base_path('app/Managers/' . $className . '.php'));

        $this->addManagerResolver($className, '\App\Managers\Contracts\\' . $className . 'Contract::class', '\App\Managers\\' . $className . '::class');
    }
}