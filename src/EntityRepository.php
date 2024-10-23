<?php

namespace Vxsoft\LaravelRepository;

use Vxsoft\LaravelRepository\Interfaces\EntityManagerInterface;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * EntityRepository Class
 *
 * This class serves as a generic repository that handles common database operations
 * for Eloquent models. It implements the EntityManagerInterface, providing methods
 * for creating, updating, deleting, and querying data from the database.
 *
 * Key features of this class:
 * - It abstracts database operations away from the controllers, encouraging separation of concerns.
 * - Handles transactions and database connections.
 * - Supports filters, sorting, and basic CRUD operations for any Eloquent model.
 * - Includes utility methods for native SQL queries and transaction management.
 *
 * @package Cbikash\LaravelRepository
 */
class EntityRepository implements EntityManagerInterface
{
    // The Eloquent model this repository interacts with
    public Model $model;

    // The database connection for executing queries
    private Connection $connection;

    // A list of entities that need to be persisted (saved) later
    private array $entitiesToPersist = [];

    /**
     * Constructor to initialize the repository with an optional model and connection.
     *
     * @param string|null $entityClass - The Eloquent model class this repository will manage.
     * @param string|null $name - The optional database connection name.
     */
    public function __construct($entityClass = null, null|string $name = null)
    {
        if($entityClass) {
            $this->model = new $entityClass();
        }

        $this->connection = DB::connection($name);
    }

    /**
     * Get the repository for a specific entity (model).
     *
     * @param string $entityClass - The fully qualified class name of the entity (model).
     * @param string|null $baseNamespace - Optional base namespace for the repository.
     *
     * @return \App\Http\Repositories\EntityRepository - The corresponding repository instance.
     *
     * @throws \Exception - If the repository class does not exist.
     *
     * <code>
     *     $object = $this->entityManager->getRepository(Model::class)->find($id)
     * </code>
     *
     * @inheritDoc
     */
    public function getRepository(string $entityClass, string | null $baseNamespace = null): mixed
    {
        // Determine the repository class name
        $baseNamespace = $baseNamespace ? rtrim($baseNamespace, '\\') . '\\' : 'App\\Http\\Repositories\\';
        $repositoryClass = $baseNamespace . class_basename($entityClass) . "Repository";

        // Ensure the repository class exists
        if (!class_exists($repositoryClass)) {
            throw new \Exception("Repository class '{$repositoryClass}' for entity '{$entityClass}' not found.");
        }

        // Instantiate and return the repository
        return App::make($repositoryClass);
    }

    /**
     * Find and return all records for the associated model.
     *
     * @return Collection|array - The collection of model instances.
     */
    public function findAll(): Collection|array
    {
        return $this->model->query()->get();
    }

    /**
     * Find records based on provided filters and optional sorting.
     *
     * @param array $filters - Filters to apply to the query.
     * @param array $orders - Sorting options for the query.
     *
     * @return Collection|array - The filtered and sorted model instances.
     */
    public function findBy(array $filters = [], array $orders = []): Collection|array
    {
        $qb = $this->model->query();
        foreach ($filters as $key => $value)
        {
            is_array($value) ? $qb->whereIn($key, $value) : $qb->where($key, $value);
        }

        if (count($orders)) $qb->orderBy($orders[0], $orders[1]);

        return $qb->get();
    }

    /**
     * Find a record by its ID.
     *
     * @param mixed $id - The ID of the record.
     *
     * @return mixed - The corresponding model instance, or null if not found.
     */
    public function getById($id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record based on the provided data.
     *
     * @param array $data - The data to be used for the new record.
     *
     * @return mixed - The created model instance.
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing record by its ID with the provided data.
     *
     * @param mixed $id - The ID of the record to update.
     * @param array $data - The new data for the record.
     *
     * @return mixed - The updated model instance, or null if not found.
     */
    public function update($id, array $data): mixed
    {
        $model = $this->getById($id);
        if($model){
            $model->update($data);
        }

        return $model;
    }

    /**
     * Delete a record by its ID.
     *
     * @param mixed $id - The ID of the record to delete.
     *
     * @return void
     */
    public function delete($id): void
    {
        $model = $this->getById($id);
        if($model){
            $model->delete();
        }
    }

    /**
     * Find a single record that matches the provided criteria.
     *
     * @param array $criteria - Filters to apply to the query.
     *
     * @return Model|Builder|null - The found model instance or null if not found.
     */
    public function findOneBy(array $criteria): Model|Builder|null
    {
        $qb = $this->model->query();
        foreach ($criteria as $key => $value)
        {
            $qb->where($key, $value);
        }

        return $qb->first();
    }

    /**
     *  @inheritDoc
     */
    public function nativeQuery(string $sql, array $bindings = [], bool $isSelect = true): array|bool
    {
        // Safely execute a SELECT query with bindings to prevent SQL injection
        if ($isSelect) {
            return DB::select($sql, $bindings);
        } else {
            // For non-SELECT queries like INSERT/UPDATE/DELETE
            return DB::statement($sql, $bindings);
        }
    }

    /**
     * Add an entity to the list of entities to be persisted.
     *
     * @param Model $entity - The entity to persist.
     *
     * @return void
     */
    public function persist(Model $entity): void
    {
        $this->entitiesToPersist[] = $entity;
    }

    /**
     * Save all the entities that have been added for persistence.
     *
     * @return void
     */
    public function flush(): void
    {
        foreach ($this->entitiesToPersist as $entity) {
            $entity->save(); // Save the entity to the database
        }

        // Clear the list after flushing
        $this->entitiesToPersist = [];
    }

    /**
     * Get the database connection instance.
     *
     * @return Connection - The database connection.
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Begin a database transaction.
     *
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit the current database transaction.
     *
     * @return void
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * Rollback the current database transaction.
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->connection->rollback();
    }
}