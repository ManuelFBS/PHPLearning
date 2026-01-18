<?php

namespace Infrastructure;

use Domain\Repositories\ProfessorRepositoryInterface;
use Domain\Repositories\StudentRepositoryInterface;
use Domain\Repositories\UserRepositoryInterface;
use Infrastructure\Database\Repositories\UserRepository;
use Infrastructure\Database\Connection;
// use Infrastructure\Database\Repositories\ProfessorRepository;
// use Infrastructure\Database\Repositories\StudentRepository;
use Domain\UseCases\Auth\LoginUseCase;
use Domain\UseCases\User\CreateUserUseCase;
use Domain\UseCases\User\DeleteUserUseCase;
use Domain\UseCases\User\GetUserUseCase;
use Domain\UseCases\User\UpdateUserUseCase;

/**
 * * Container de Inyección de Dependencias...
 * * Centraliza la creación de objetos y sus dependencias...
 */
class Container {}

?>