<?php

namespace Domain\UseCases\User;

use Domain\Repositories\UserRepositoryInterface;

class GetAllUsersUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(
                UserRepositoryInterface $userRepository
        ) {
                $this->userRepository = $userRepository;
        }

        public function execute(): array
        {
                $users = $this->userRepository->findAll();

                return [
                        'success' => true,
                        'message' => 'Listado de usuarios',
                        'data' => $users
                ];
        }
}

?>