# Laravel Repository Package

**Author**: Bikas Chaudhary <vcbikas123@gmail.com>  
**Version**: 1.0.0

## Overview

The Laravel Repository Package provides a clean, structured approach to managing and abstracting database interactions in Laravel applications. By implementing repositories, it separates the data access logic from the business logic, promoting a more maintainable and scalable architecture.

## Features

- **Entity Repositories**: Automatically generate repository classes tied to your Laravel models for a cleaner and more organized codebase.
- **CRUD Operations**: Provides a consistent interface for create, read, update, and delete operations.
- **Custom Query Logic**: Easily extend and customize repositories with your own query logic.
- **Transaction Management**: Built-in support for database transactions using repository methods.
- **Native Queries**: Safely execute raw SQL queries with automatic binding for better security and flexibility.

## Installation

1. Install the package via Composer:

    ```bash
    composer require vxsoft/laravel-repository
    ```

2. Publish the configuration (if needed):

    ```bash
    php artisan vendor:publish --tag=repository-config
    ```

3. Start generating repositories:

    ```bash
    php artisan make:repository ModelName
    ```

## Usage

Once installed, you can generate repositories using the provided Artisan command. For example, to generate a repository for a `User` model:

```bash
php artisan make:repository User
```
