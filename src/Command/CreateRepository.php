<?php

namespace Vxsoft\LaravelRepository\Command;

use Illuminate\Console\Command;

/**
 * Class CreateRepository
 *
 * This Laravel command is used to generate a new repository file for a specified model.
 * It automates the creation of repository classes, ensuring consistency and ease of maintenance
 * in handling model data.
 */
class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository file for the specified model';

    /**
     * Execute the console command.
     *
     * This method is triggered when the command is run, creating a new repository file
     * for the specified model. It also ensures that the Repositories directory exists.
     *
     * @return void
     */
    public function handle(): void
    {
        // Retrieve the name of the model from the command argument
        $modelName = $this->argument('name');

        // Get the base path and root namespace from the config
        $basePath = config('repository.generator.basePath');
        $rootNamespace = config('repository.generator.rootNamespace');

        // Retrieve the paths from the config
        $modelsPath = config('repository.generator.paths.models');
        $repositoriesPath = config('repository.generator.paths.repositories');

        // Construct the model class and check if it exists
        $modelClass = "{$rootNamespace}".str_replace([DIRECTORY_SEPARATOR], '\\', $modelsPath)."\\{$modelName}";

        if (!class_exists($modelClass)) {
            $this->error("Model '{$modelClass}' does not exist.");
            return;
        }

        // Construct the file path for the repository based on the config
        $filePath = "{$basePath}/{$repositoriesPath}/{$modelName}Repository.php";
        $repositoryNamespace = $rootNamespace . str_replace([DIRECTORY_SEPARATOR], '\\', $repositoriesPath);
        
        // Ensure the Repositories directory exists
        if (!is_dir("{$basePath}/{$repositoriesPath}")) {
            mkdir("{$basePath}/{$repositoriesPath}", 0755, true);
        }

        // Check if the repository already exists
        if (file_exists($filePath)) {
            $this->error("Repository '{$filePath}' already exists.");
            return;
        }

        // Generate the repository file content and create the file
        $fileContent = $this->getContent($modelName,  $modelClass, $repositoryNamespace);
        file_put_contents($filePath, $fileContent);

        // Display success message
        $this->info("Repository created successfully: {$filePath}");
    }

    /**
     * Generate the content for the repository file.
     *
     * This method generates a PHP class template for the repository, including basic methods
     * and placeholders for extending functionality. The generated repository will extend
     * the base `Repository` class and can be further customized.
     *
     * @param string $modelName The name of the model for which the repository is being created.
     * @return string The generated PHP class content for the repository.
     */
    private function getContent(string $modelName, string $modelClass, string $repositoryNamespace): string
    {
        // Building the repository class content
        return <<<PHP
            <?php
            
                namespace {$repositoryNamespace};
                
                use {$modelClass};
                use Vxsoft\\LaravelRepository\\Repository;
                use Illuminate\Support\Collection;
                
                /**
                 * {$modelName}Repository
                 * 
                 * This class provides an abstraction for interacting with the {$modelName} model.
                 * It extends the base Repository class, leveraging common CRUD operations while
                 * allowing custom query logic for the {$modelName} model.
                 *
                 * @extends Repository<{$modelName}>
                 * 
                 * @method {$modelName}|null findOneBy(array \$criteria) Retrieve a single {$modelName} record based on criteria
                 * @method {$modelName}|null getById(mixed \$id) Retrieve a {$modelName} record by ID
                 * @method Collection|{$modelName}[]|null findBy(array \$filters = [], array \$orders = []) Retrieve records based on filters and ordering
                 * @method {$modelName} create(array \$data) Create a new {$modelName} record
                 * @method {$modelName} update(mixed \$id, array \$data) Update an existing {$modelName} record by ID
                 * @method void delete(mixed \$id) Delete a {$modelName} record by ID
                 */
                class {$modelName}Repository extends Repository
                {
                    /**
                     * Repository constructor.
                     *
                     * This constructor binds the repository to the {$modelName} model.
                     */
                    public function __construct()
                    {
                        parent::__construct({$modelName}::class);
                    }
                
                    // Add your repository logic here
                }
            PHP;
    }
}
