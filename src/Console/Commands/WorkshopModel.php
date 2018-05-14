<?php namespace Yorki\Workshop\Console\Commands;

use \Illuminate\Console\Command;

class WorkshopModel extends Command
{
    const MODEL_NAMESPACE = 'App\Models';
    const REPOSITORIES_NAMESPACE = 'App\Repositories';

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $modelNamespace;

    /**
     * @var array
     */
    protected $modelAttributes;

    /**
     * @var string
     */
    protected $repositoryNamespace;

    /**
     * @var string
     */
    protected $signature = 'workshop:model';

    /**
     * @var string
     */
    protected $description = 'Makes new workshop';

    public function handle()
    {
        $this->modelAttributes = [];
        $this->output->title('1. Create new model class');

        do {
            $this->askForModel();
        } while (!$this->confirmModel());

        $this->call('make:repository-model', [
            'model' => $this->modelName,
            '--model-namespace' => $this->modelNamespace,
            '--attributes' => $this->getAttributes(),
        ]);

        $this->output->title('2. Create new model repository class');

        if ($this->confirm('2. Make repository?')) {
            do {
                $this->askForRepository();
            } while (!$this->confirmRepository());

            $this->call('make:repository', [
                'model' => $this->modelName,
                '--model-namespace' => $this->modelNamespace,
            ]);
        }

        if ($this->confirm('3. Make migration?')) {
            $this->call('make:repository-migration', [
                'model' => $this->modelName,
                '--model-namespace' => $this->modelNamespace,
            ]);
        }

        if ($this->confirm('4. Make API controller?')) {
            $this->call('make:repository-api', [
                'model' => $this->modelName,
                '--model-namespace' => $this->modelNamespace,
                '--api-namespace' => 'App\Http\Controllers\Api',
                '--api-repository-contract' => 'App\Repositories\Contracts\\' . $this->modelName . 'RepositoryContract',
            ]);
        }
    }

    protected function askForRepository()
    {
        $this->repositoryNamespace = $this->ask('1.2 Repository class namespace', $this->repositoryNamespace ?: self::REPOSITORIES_NAMESPACE);
    }

    /**
     * @return bool
     */
    protected function confirmRepository()
    {
        $this->output->block(
            implode(PHP_EOL, [
                'Repository class namespace: ' . $this->repositoryNamespace
            ])
        );

        return $this->confirm('Is provided data correct?');
    }

    /**
     * @return bool
     */
    protected function askAttribute()
    {
        if (!$this->confirm('Add model attribute?')) {
            return false;
        }

        $name = $this->ask('Model attribute name');
        $type = $this->output->choice('Model attribute type', [
            'string',
            'integer',
            'float',
            'boolean',
            'date',
        ]);

        $this->modelAttributes[$name] = $type;

        return true;
    }

    /**
     * @return string
     */
    protected function getAttributes()
    {
        $result = [];

        foreach ($this->modelAttributes as $name => $type) {
            $result[] = $name . '=' . $type;
        }

        return implode(',', $result);
    }

    protected function askForModel()
    {
        $this->modelName = $this->ask('1.1 Model class name', $this->modelName);
        $this->modelNamespace = $this->ask('1.2 Model class namespace', $this->modelNamespace ?: self::MODEL_NAMESPACE);

        while ($this->askAttribute());
    }

    /**
     * @return bool
     */
    protected function confirmModel()
    {
        $this->output->block(
            implode(PHP_EOL, [
                'Model class name: ' . $this->modelName,
                'Model class namespace: ' . $this->modelNamespace,
                'Model attributes: ' . $this->getAttributes(),
            ])
        );

        return $this->confirm('Is provided data correct?');
    }
}