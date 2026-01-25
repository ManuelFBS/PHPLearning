<?php

namespace Presentation\Controllers;

use Domain\UseCases\Student\CreateStudentUseCase;
use Domain\UseCases\Student\DeleteStudentUseCase;
use Domain\UseCases\Student\GetAllStudentsUseCase;
use Domain\UseCases\Student\GetStudentUseCase;
use Domain\UseCases\Student\UpdateStudentUseCase;

class StudentController
{
        private CreateStudentUseCase $createStudentUseCase;
        private GetAllStudentsUseCase $getAllStudentsUseCase;
        private GetStudentUseCase $getStudentUseCase;
        private UpdateStudentUseCase $updateStudentUseCase;
        private DeleteStudentUseCase $deleteStudentUseCase;
}

?>