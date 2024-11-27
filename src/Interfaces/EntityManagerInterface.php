<?php
namespace Vxsoft\LaravelRepository\Interfaces;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as IlluminateCollection;

/**
 * Interface EntityManagerInterface
 *
 * This interface defines the contract for an Entity Manager in the application.
 * It outlines essential methods for managing entities, including CRUD operations,
 * database transactions, and query execution. Any class implementing this interface
 * should provide concrete implementations for these methods to interact with the underlying
 * data models.
 *
 * @author Bikas Chaudhary <vcbikas123@gmail.com>
 */
interface EntityManagerInterface
{
    /**
     * Get the repository for a specific entity (model).
     *
     * @param string $entityClass - The fully qualified class name of the entity (model).
     *
     * @throws \Exception - If the repository class does not exist.
     *
     * <code>
     *     $object = $this->entityManager->getRepository(Model::class)->find($id)
     * </code>
     *
     * @inheritDoc
     */
    public function getRepository(string $entityClass): mixed;

    /**
     * Get the current model instance.
     *
     * This method returns the model associated with the repository.
     * The returned object could either be an instance of the Model class or any object that extends Model.
     * Useful when you need to access the model for further operations in the repository.
     *
     * @return Model|mixed The model instance being used by the repository.
     */
    public function getModel();

    /**
     * Get the name of the table associated with the model.
     *
     * This method retrieves the table name from the model instance.
     * It assumes that the model has a method `getTable()` which returns the database table's name associated with the model.
     * Useful when you need to dynamically reference the model's table in queries.
     *
     * @return string The table name associated with the current model.
     */
    public function getTable();

    /**
     * Create a new query builder instance for the current table.
     *
     * This method initiates a query builder for the table associated with the current model.
     * It uses the `connection` property to access the database connection and starts a new query on the table retrieved from `getTable()`.
     * Useful when you need to build complex queries programmatically in the repository.
     *
     * @return \Illuminate\Database\Query\Builder A new query builder instance for the table.
     */
    public function createQueryBuilder();

    /**
     * Find a single record that matches the provided criteria.
     *
     * @param array $criteria - Filters to apply to the query.
     *
     * @return mixed - The found model instance or null if not found.
     */
    public function findOneBy(array $criteria): mixed;

    /**
     * @return IlluminateCollection Return all values
     */
    public function findAll(): IlluminateCollection;

    /**
     * @param array $filters
     * @param array $orders
     * @return IlluminateCollection|array
     *
     * Return values according to filter or criteria, it also accepts orders parameter and return order according to it
     */
    public function findBy(array $filters, array $orders = []): IlluminateCollection|array;

    /**
     * @param $id
     * @return mixed
     *
     * Return a single value according to id
     */
    public function getById($id): mixed;

    /**
     * @param array $data
     * @return mixed
     *
     * Create an object according to given parameters
     */
    public function create(array $data): mixed;

    /**
     * @param $id
     * @param array $data
     * @return mixed
     *
     * Update an object by its id
     */
    public function update($id, array $data): mixed;

    /**
     * @param $id
     * @return void
     *
     * Delete object
     */
    public function delete($id): void;

    /**
     * Execute a native SQL query with optional bindings.
     *
     * @param string $sql - The raw SQL query to execute.
     * @param array $bindings - Optional bindings for the query parameters.
     * @param bool $isSelect - If true, returns select query results. If false, for non-select queries (e.g., insert/update).
     *
     * @return array|bool - The result of the query or true if successful for non-select queries.
     */
    public function nativeQuery(string $sql, array $bindings = [], bool $isSelect = true): array|bool;


    /**
     * @param Model $entity
     * @return void
     * Prepare for entity
     */
    public function persist(Model $entity): void;

    /**
     * @return void
     * Commit the database
     */
    public function flush(): void;

    /**
     * @return Connection
     */
    public function getConnection(): Connection;

    /**
     * @return void
     * Begin the database transaction
     * @throws \Throwable
     */
    public function beginTransaction(): void;

    /**
     * @return void
     * Commit database transaction
     * @throws \Throwable
     */
    public function commit(): void;

    /**
     * @return void
     * Rollback the database
     * @throws \Throwable
     */
    public function rollback(): void;
}