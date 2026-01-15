<?php

namespace Domain\UseCases\User;

use Domain\Repositories\UserRepositoryInterface;

class GetUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(string $username): array
        {
                $user = $this->userRepository->findByUsername($username);

                if ($user === null) {
                        return [
                                'success' => false,
                                'message' => 'Usuario no encontrado',
                                'data' => null
                        ];
                }

                return [
                        'success' => true,
                        'message' => 'Usuario encontrado',
                        'data' => $user
                ];
        }
}

?>