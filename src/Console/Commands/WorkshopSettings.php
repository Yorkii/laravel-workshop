<?php namespace Yorki\Workshop\Console\Commands;

class WorkshopSettings extends WorkshopCommand
{
    /**
     * @var string
     */
    protected $signature = 'workshop:settings';

    /**
     * @var string
     */
    protected $description = 'Makes settings';

    public function handle()
    {
        if (!$this->checkDependencies()) {
            return;
        }

        $paths = [
            base_path('app/Models'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }
        }

        $this->files->copy(__DIR__ . '/Stubs/database/migrations/2017_11_29_114005_create_setting_table.php', base_path('database/migrations/2017_11_29_114005_create_setting_table.php'));
        $this->call('migrate');

        $this->copyAdminViews('general', [
            'settings',
        ]);

        $this->addRoutesFromFile(__DIR__ . '/Stubs/routes/web.settings.stub');

        $this->files->copy(__DIR__ . '/Stubs/Models/Setting.php', base_path('app/Models/Setting.php'));
        $this->call('make:repository', [
            'model' => 'Setting',
            '--model-namespace' => 'App\Models',
        ]);

        $this->copyManager('SettingManager');

        $this->files->copy(__DIR__ . '/Stubs/Controllers/GeneralController.stub', base_path('app/Http/Controllers/Admin/GeneralController.php'));

        $this->output->success('Settings created');
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