<?php namespace Yorki\Workshop\Console\Commands;

class WorkshopStaticpages extends WorkshopCommand
{
    /**
     * @var string
     */
    protected $signature = 'workshop:static-pages';

    /**
     * @var string
     */
    protected $description = 'Makes static pages';

    public function handle()
    {
        if (!$this->checkDependencies()) {
            return;
        }

        $paths = [
            base_path('app/Models'),
            base_path('app/Managers/Contracts'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }
        }

        $this->files->copy(__DIR__ . '/Stubs/database/migrations/2017_12_09_070127_create_static_page_table.php', base_path('database/migrations/2017_12_09_070127_create_static_page_table.php'));
        $this->call('migrate');

        $this->copyAdminViews('static', [
            'add',
            'index',
            'single',
        ]);

        $this->addRoutesFromFile(__DIR__ . '/Stubs/routes/web.static.stub');

        $this->files->copy(__DIR__ . '/Stubs/Models/StaticPage.php', base_path('app/Models/StaticPage.php'));
        $this->call('make:repository', [
            'model' => 'StaticPage',
            '--model-namespace' => 'App\Models',
        ]);

        $this->copyManager('LinkManager');
        $this->copyManager('StaticPageManager');

        $this->files->copy(__DIR__ . '/Stubs/Controllers/StaticController.stub', base_path('app/Http/Controllers/Admin/StaticController.php'));

        $this->output->success('Static pages created');
    }

    /**
     * @return bool
     */
    protected function checkDependencies()
    {
        if (!file_exists(base_path('resources/views/admin/dashboard.blade.php'))) {
            $this->output->error('Admin panel is not installed!');
            $this->output->success('Type "php artisan workshop:admin" to install');

            return false;
        }

        return true;
    }
}