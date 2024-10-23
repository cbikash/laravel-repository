<?php

namespace Vxsoft\LaravelRepository;

use Vxsoft\LaravelRepository\EntityRepository;

/**
 * Repository Base Class
 *
 * This class acts as a base repository class, extending the functionality of the
 * EntityRepository class. It provides a foundation for creating specific repository
 * classes that will handle the data access layer for entities in a Laravel application.
 *
 * By extending EntityRepository, this base class can inherit common CRUD operations,
 * while allowing for more specialized data retrieval methods to be added in the future.
 *
 * @package Cbikash\LaravelRepository
 */
class Repository extends EntityRepository
{

}
