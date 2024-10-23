# Laravel Repository Package

**Author**: Bikas Chaudhary <vcbikas123@gmail.com>  
**Version**: 1.0.0

## Overview

The Laravel Repository Package simplifies database interactions by providing a clean, organized approach to managing data in Laravel applications. With repositories, the package decouples data access logic from business logic, making your codebase more maintainable and scalable.

## Features

- **Entity Repositories**: Automatically generate repository classes linked to Laravel models, promoting a more organized codebase.
- **CRUD Operations**: Consistent interfaces for Create, Read, Update, and Delete operations.
- **Custom Query Logic**: Easily extend repositories to include custom queries tailored to your application's needs.
- **Transaction Management**: Handle database transactions through repository methods for cleaner and safer operations.
- **Native Queries**: Execute raw SQL queries securely with automatic parameter binding.

## Installation

1. Install via Composer:

    ```bash
    composer require vxsoft/laravel-repository
    ```

2. (Optional) Publish the configuration:

    ```bash
    php artisan vendor:publish --tag=repository-config
    ```

3. Generate repositories:

    ```bash
    php artisan make:repository ModelName
    ```

## Usage

Once installed, you can generate repositories using the provided Artisan command. For example, to generate a repository for a `User` model:

```bash
php artisan make:repository User
```
This will generate App\Http\Repositories\UserRepository.
```php
<?php

namespace App\Http\Repositories;

use App\Models\User;
use Vxsoft\LaravelRepository\Repository;
use Illuminate\Support\Collection;

/**
 * UserRepository
 *
 * Provides an abstraction layer for interacting with the User model,
 * extending the base Repository class to leverage CRUD operations and allow
 * for custom query logic.
 *
 * @extends Repository<User>
 */
class UserRepository extends Repository
{
    /**
     * Constructor binds the repository to the User model.
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

    // Custom repository logic can be added here
}
```

## Adding Custom Logic
For instance, let's add custom logic to filter users by status:

```php
public function getUserList(array $filters = []): mixed
{
    $qb = $this->model->select('*');

    if (!empty($filters['status'])) {
        $qb->where('status', $filters['status']);
    }

    return $qb;
}
```

## Controller Example
In your controller, you can inject the repository and use it to retrieve users:
```php 
public function __construct(private readonly UserRepository $userRepository)
{
}

/**
 * User List API
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request)
{
    $users = $this->userRepository->getUserList($request->all())->get();

    return response()->json([
        'success' => true,
        'message' => 'List all users',
        'data' => UserResource::collection($users),
    ]);
}
```
## Using EntityManager Interface
Alternatively, you can access the repository via EntityManagerInterface:
```php 
public function __construct(private readonly EntityManagerInterface $entityManager)
{
}

/**
 * User List API
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request)
{
    $users = $this->entityManager
        ->getRepository(User::class)
        ->getUserList($request->all())
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'List all users',
        'data' => UserResource::collection($users),
    ]);
}
```
## Available Repository Functions
Here are some of the key functions available within your repositories:
```php 
/**
 * Find a single record based on criteria.
 * @param array $criteria
 * @return Model|array|Builder|null
 */
public function findOneBy(array $criteria): Model|array|Builder|null;

/**
 * Return all records.
 * @return mixed|Collection|null
 */
public function findAll(): mixed;

/**
 * Return records based on filters and ordering.
 * @param array $filters
 * @param array $orders
 * @return mixed
 */
public function findBy(array $filters = [], array $orders = []): mixed;

/**
 * Retrieve a record by its ID.
 * @param $id
 * @return mixed
 */
public function getById($id): mixed;

/**
 * Create a new record with the provided data.
 * @param array $data
 * @return mixed
 */
public function create(array $data): mixed;

/**
 * Update an existing record by ID.
 * @param $id
 * @param array $data
 * @return mixed
 */
public function update($id, array $data): mixed;

/**
 * Delete a record by ID.
 * @param $id
 * @return void
 */
public function delete($id): void;

/**
 * Execute a raw SQL query with optional bindings.
 * @param string $sql
 * @param array $bindings
 * @param bool $isSelect
 * @return array|bool
 */
public function nativeQuery(string $sql, array $bindings = [], bool $isSelect = true): array|bool;

/**
 * Persist the entity.
 * @param Model $entity
 * @return void
 */
public function persist(Model $entity): void;

/**
 * Commit the transaction.
 * @return void
 */
public function flush(): void;

/**
 * Get the database connection.
 * @return Connection
 */
public function getConnection(): Connection;

/**
 * Begin a database transaction.
 * @return void
 * @throws \Throwable
 */
public function beginTransaction(): void;

/**
 * Commit the database transaction.
 * @return void
 * @throws \Throwable
 */
public function commit(): void;

/**
 * Rollback the transaction.
 * @return void
 * @throws \Throwable
 */
public function rollback(): void;
```

## License
[MIT](https://choosealicense.com/licenses/mit/)


