<?php

namespace Infrastructure;

// * ---------------------------------------------------------------------------------------
use Domain\Repositories\ProfessorRepositoryInterface;
use Domain\UseCases\Professor\CreateProfessorUseCase;
use Domain\UseCases\Professor\DeleteProfessorUseCase;
use Domain\UseCases\Professor\GetAllProfessorsUseCase;
use Domain\UseCases\Professor\GetProfessorUseCase;
use Domain\UseCases\Professor\UpdateProfessorUseCase;
use Infrastructure\Database\Repositories\ProfessorRepository;
use Presentation\Controllers\ProfessorController;
// * ---------------------------------------------------------------------------------------
use Domain\Repositories\StudentRepositoryInterface;
use Infrastructure\Database\Repositories\StudentRepository;
// * ---------------------------------------------------------------------------------------
use Domain\Repositories\UserRepositoryInterface;
use Domain\UseCases\User\CreateUserUseCase;
use Domain\UseCases\User\DeleteUserUseCase;
use Domain\UseCases\User\GetUserUseCase;
use Domain\UseCases\User\UpdateUserUseCase;
use Infrastructure\Database\Repositories\UserRepository;
use Presentation\Controllers\UserController;
// * ---------------------------------------------------------------------------------------
use Domain\UseCases\Auth\LoginUseCase;
use Domain\UseCases\User\GetAllUsersUseCase;
use Presentation\Controllers\AuthController;
// * ---------------------------------------------------------------------------------------
use Infrastructure\Database\Connection;

/**
 * ~ Container de Inyección de Dependencias... * ~ Centraliza la creación de objetos y sus dependencias...
 */
class Container
{
        private static ?Connection $connection = null;
        private static ?UserRepositoryInterface $userRepository;
        private static ?ProfessorRepositoryInterface $professorRepository;
        private static ?StudentRepositoryInterface $studentRepository;

        // * Obtener la conexión a la base de datos (Singleton)...
        public static function getConnection(): Connection
        {
                if (self::$connection === null) {
                        self::$connection = new Connection();
                }

                return self::$connection;
        }

        // * --------------------------------- OBTENCION DE LOS REPOSITORIOS -------------------------------------- * //

        // * Obtener el repositorio de usuarios...
        public static function getUserRepository(): UserRepositoryInterface
        {
                if (self::$userRepository === null) {
                        self::$userRepository = new UserRepository(self::getConnection());
                }

                return self::$userRepository;
        }

        // * Obtener el repositorio de profesores...
        public static function getProfessorRepository(): ProfessorRepositoryInterface
        {
                if (self::$professorRepository === null) {
                        self::$professorRepository = new ProfessorRepository(self::getConnection());
                }

                return self::$professorRepository;
        }

        // * Obtener el repositorio de estudiantes...
        public static function getStudentRepository(): StudentRepositoryInterface
        {
                if (self::$studentRepository === null) {
                        self::$studentRepository = new StudentRepository(self::getConnection());
                }

                return self::$studentRepository;
        }

        // * ----------------------------------------- CASOS DE USO: Login ---------------------------------------------- * //

        // * Obtener el caso de uso de login...
        public static function getLoginUseCase(): LoginUseCase
        {
                return new LoginUseCase(self::getUserRepository());
        }

        // * -------------------------------------- CASOS DE USO: Professor ------------------------------------------- * //

        public static function getCreateProfessorUseCase(): CreateProfessorUseCase
        {
                return new CreateProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // * Obtener el caso de uso para obtener todos los profesores...
        public static function getGetAllProfessorsUseCase(): GetAllProfessorsUseCase
        {
                return new GetAllProfessorsUseCase(
                        self::getProfessorRepository()
                );
        }

        // * Obtener el caso de uso de obtener profesor...
        public static function getGetProfessorUseCase(): GetProfessorUseCase
        {
                return new GetProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // * Obtener el caso de uso de actualizar profesor...
        public static function getUpdateProfessorUseCase(): UpdateProfessorUseCase
        {
                return new UpdateProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // * Obtener el caso de uso de eliminar usuario...
        public static function getDeleteProfessorUseCase(): DeleteProfessorUseCase
        {
                return new DeleteProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // * ----------------------------------------- CASOS DE USO: User ---------------------------------------------- * //

        // * Obtener el caso de uso de crear usuario...
        public static function getCreateUserUseCase(): CreateUserUseCase
        {
                return new CreateUserUseCase(
                        self::getUserRepository(),
                        self::getProfessorRepository(),
                        self::getStudentRepository()
                );
        }

        // * Obtener el caso de uso para obtener todos los usuarios...
        public static function getGetAllUsersUseCase(): GetAllUsersUseCase
        {
                return new GetAllUsersUseCase(
                        self::getUserRepository()
                );
        }

        // * Obtener el caso de uso de obtener usuario...
        public static function getGetUserUseCase(): GetUserUseCase
        {
                return new GetUserUseCase(
                        self::getUserRepository()
                );
        }

        // * Obtener el caso de uso de actualizar usuario...
        public static function getUpdateUserUseCase(): UpdateUserUseCase
        {
                return new UpdateUserUseCase(
                        self::getUserRepository()
                );
        }

        // * Obtener el caso de uso de eliminar usuario...
        public static function getDeleteUserUseCase(): DeleteUserUseCase
        {
                return new DeleteUserUseCase(
                        self::getUserRepository()
                );
        }

        // * ------------------------------- OBTENCION DE LOS CONTROLADORES ---------------------------------- * //

        // * Obtener el controlador de autenticación...
        public static function getAuthController(): AuthController
        {
                return new AuthController(self::getLoginUseCase());
        }

        // * Obtener el controlador de usuarios...
        public static function getUserController(): UserController
        {
                return new UserController(
                        self::getCreateUserUseCase(),
                        self::getGetAllUsersUseCase(),
                        self::getGetUserUseCase(),
                        self::getUpdateUserUseCase(),
                        self::getDeleteUserUseCase()
                );
        }

        // * Obtener el controlador de profesores...
        public static function getProfessorController(): ProfessorController
        {
                return new ProfessorController(
                        self::getCreateProfessorUseCase(),
                        self::getGetAllProfessorsUseCase(),
                        self::getGetProfessorUseCase(),
                        self::getUpdateProfessorUseCase(),
                        self::getDeleteProfessorUseCase()
                );
        }

        // * Obtener el controlador de estudiantes...
        // public static function getStudentController(): StudentController {}
}

?>