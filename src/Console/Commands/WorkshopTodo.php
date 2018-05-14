<?php namespace Yorki\Workshop\Console\Commands;

class WorkshopTodo extends WorkshopCommand
{
    /**
     * @var string
     */
    protected $signature = 'workshop:todo';

    /**
     * @var string
     */
    protected $description = 'Makes todo';

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

        $this->files->copy(__DIR__ . '/Stubs/database/migrations/2017_12_08_110908_create_todo_table.php', base_path('database/migrations/2017_12_08_110908_create_todo_table.php'));
        $this->files->copy(__DIR__ . '/Stubs/database/migrations/2017_12_08_121307_create_todo_comment_table.php', base_path('database/migrations/2017_12_08_121307_create_todo_comment_table.php'));
        $this->call('migrate');

        $this->copyAdminViews('todo', [
            'index',
            'single',
        ]);

        $this->addRoutesFromFile(__DIR__ . '/Stubs/routes/web.todo.stub');

        $this->files->copy(__DIR__ . '/Stubs/Models/Todo.php', base_path('app/Models/Todo.php'));
        $this->files->copy(__DIR__ . '/Stubs/Models/TodoComment.php', base_path('app/Models/TodoComment.php'));

        $this->call('make:repository', [
            'model' => 'Todo',
            '--model-namespace' => 'App\Models',
        ]);

        $this->call('make:repository', [
            'model' => 'TodoComment',
            '--model-namespace' => 'App\Models',
        ]);

        $this->files->copy(__DIR__ . '/Stubs/Controllers/TodoController.stub', base_path('app/Http/Controllers/Admin/TodoController.php'));

        $this->output->success('Todo created');
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