<?php

namespace Infrastructure;

// * ------------------------------ Professor ---------------------------------------
use Domain\Repositories\ProfessorRepositoryInterface;
use Domain\UseCases\Professor\CreateProfessorUseCase;
use Domain\UseCases\Professor\DeleteProfessorUseCase;
use Domain\UseCases\Professor\GetAllProfessorsUseCase;
use Domain\UseCases\Professor\GetProfessorUseCase;
use Domain\UseCases\Professor\UpdateProfessorUseCase;
use Infrastructure\Database\Repositories\ProfessorRepository;
// * ------------------------------- Student ----------------------------------------
use Domain\Repositories\StudentRepositoryInterface;
use Domain\UseCases\Student\CreateStudentUseCase;
use Domain\UseCases\Student\DeleteStudentUseCase;
use Domain\UseCases\Student\GetAllStudentsUseCase;
use Domain\UseCases\Student\GetStudentUseCase;
use Domain\UseCases\Student\UpdateStudentUseCase;
use Infrastructure\Database\Repositories\StudentRepository;
// * ------------------------------- User -------------------------------------------
use Domain\Repositories\UserRepositoryInterface;
use Domain\UseCases\User\CreateUserUseCase;
use Domain\UseCases\User\DeleteUserUseCase;
use Domain\UseCases\User\GetAllUsersUseCase;
use Domain\UseCases\User\GetUserUseCase;
use Domain\UseCases\User\UpdateUserUseCase;
use Infrastructure\Database\Repositories\UserRepository;
// * ------------------------------ Login -------------------------------------------
use Domain\UseCases\Auth\LoginUseCase;
// * --------------------------- Controllers ---------------------------------------
use Presentation\Controllers\AuthController;
use Presentation\Controllers\ProfessorController;
use Presentation\Controllers\StudentController;
use Presentation\Controllers\UserController;
// * --------------------------- Connection --------------------------------------
use Infrastructure\Database\Connection;

/**
 * ~ Container de Inyección de Dependencias... * ~ Centraliza la creación de objetos y sus dependencias...
 */
class Container
{
        private static ?Connection $connection = null;
        private static ?UserRepositoryInterface $userRepository = null;
        private static ?ProfessorRepositoryInterface $professorRepository = null;
        private static ?StudentRepositoryInterface $studentRepository = null;

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
        // > Obtener el caso de uso de login...
        public static function getLoginUseCase(): LoginUseCase
        {
                return new LoginUseCase(self::getUserRepository());
        }

        // * -------------------------------------- CASOS DE USO: Professor ------------------------------------------- * //
        // > Obtener caso de uso para registrar un profesor...
        public static function getCreateProfessorUseCase(): CreateProfessorUseCase
        {
                return new CreateProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // > Obtener el caso de uso para mostrar todos los profesores...
        public static function getGetAllProfessorsUseCase(): GetAllProfessorsUseCase
        {
                return new GetAllProfessorsUseCase(
                        self::getProfessorRepository()
                );
        }

        // > Obtener el caso de uso para mostrar un profesor...
        public static function getGetProfessorUseCase(): GetProfessorUseCase
        {
                return new GetProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // > Obtener el caso de uso para actualizar un profesor...
        public static function getUpdateProfessorUseCase(): UpdateProfessorUseCase
        {
                return new UpdateProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // > Obtener el caso de uso para eliminar un profesor...
        public static function getDeleteProfessorUseCase(): DeleteProfessorUseCase
        {
                return new DeleteProfessorUseCase(
                        self::getProfessorRepository()
                );
        }

        // * ---------------------------------------- CASOS DE USO: Student -------------------------------------------- * //
        // > Obtener caso de uso para registrar un estudiante...
        public static function getCreateStudentUseCase(): CreateStudentUseCase
        {
                return new CreateStudentUseCase(
                        self::getStudentRepository()
                );
        }

        // > Obtener el caso de uso para mostrar todos los estudiantes...
        public static function getGetAllStudentsUseCase(): GetAllStudentsUseCase
        {
                return new GetAllStudentsUseCase(
                        self::getStudentRepository()
                );
        }

        // > Obtener el caso de uso para mostrar un estudiante...
        public static function getGetStudentUseCase(): GetStudentUseCase
        {
                return new GetStudentUseCase(
                        self::getStudentRepository()
                );
        }

        // > Obtener el caso de uso para actualizar un estudiante...
        public static function getUpdateStudentUseCase(): UpdateStudentUseCase
        {
                return new UpdateStudentUseCase(
                        self::getStudentRepository()
                );
        }

        // > Obtener el caso de uso para eliminar un estudiante...
        public static function getDeleteStudentUseCase(): DeleteStudentUseCase
        {
                return new DeleteStudentUseCase(
                        self::getStudentRepository()
                );
        }

        // * ----------------------------------------- CASOS DE USO: User ---------------------------------------------- * //
        // > Obtener el caso de uso de crear usuario...
        public static function getCreateUserUseCase(): CreateUserUseCase
        {
                return new CreateUserUseCase(
                        self::getUserRepository(),
                        self::getProfessorRepository(),
                        self::getStudentRepository()
                );
        }

        // > Obtener el caso de uso para mostrar todos los usuarios...
        public static function getGetAllUsersUseCase(): GetAllUsersUseCase
        {
                return new GetAllUsersUseCase(
                        self::getUserRepository()
                );
        }

        // > Obtener el caso de uso para mostrar un usuario...
        public static function getGetUserUseCase(): GetUserUseCase
        {
                return new GetUserUseCase(
                        self::getUserRepository()
                );
        }

        // > Obtener el caso de uso para actualizar usuario...
        public static function getUpdateUserUseCase(): UpdateUserUseCase
        {
                return new UpdateUserUseCase(
                        self::getUserRepository()
                );
        }

        // > Obtener el caso de uso para eliminar usuario...
        public static function getDeleteUserUseCase(): DeleteUserUseCase
        {
                return new DeleteUserUseCase(
                        self::getUserRepository()
                );
        }

        // * ------------------------------- OBTENCION DE LOS CONTROLADORES ---------------------------------- * //
        // > Obtener el controlador de Auth...
        public static function getAuthController(): AuthController
        {
                return new AuthController(self::getLoginUseCase());
        }

        // > Obtener el controlador de User...
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

        // > Obtener el controlador de Professor...
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

        // > Obtener el controlador de Student...
        public static function getStudentController(): StudentController
        {
                return new StudentController(
                        self::getCreateStudentUseCase(),
                        self::getGetAllStudentsUseCase(),
                        self::getGetStudentUseCase(),
                        self::getUpdateStudentUseCase(),
                        self::getDeleteStudentUseCase()
                );
        }
}

?>