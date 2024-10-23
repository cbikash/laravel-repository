<?php
namespace Vxsoft\LaravelRepository\Interfaces;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @param string $entityClass
     * @param string|null $baseNamespace
     */
    public function getRepository(string $entityClass, null|string $baseNamespace = null): mixed;

    /**
     * @param array $criteria
     * @return Model|array|Builder|null Find One result according to given criteria
     * Find One result according to given criteria
     */
    public function findOneBy(array $criteria): Model|array|Builder|null;

    /**
     * @return mixed | Collection | null
     * Return all values
     */
    public function findAll(): mixed;

    /**
     * @param array $filters
     * @param array $orders
     * @return mixed
     *
     * Return values according to filter or criteria, it also accepts orders parameter and return order according to it
     */
    public function findBy(array $filters = [], array $orders = []): mixed;

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