<?php

namespace Domain\UseCases\Auth;

use Domain\Repositories\UserRepositoryInterface;

/**
 * ~ Caso de uso: Iniciar sesión...
 * ~ Contiene la lógica de negocio para autenticar un usuario...
 */
class LoginUseCase
{
        private UserRepositoryInterface $userRepository;

        // * Inyección de dependencias: recibimos el repositorio por el constructor...
        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        /**
         * * Ejecutar el caso de uso de login...
         *
         * @param string $username Nombre de usuario
         * @param string $password Contraseña sin encriptar
         * @return array ['success' => bool, 'user' => User|null, 'message' => string]
         */
        public function execute(string $username, string $password): array
        {
                // > 1. Buscar el usuario...
                $user = $this->userRepository->findByUsername($username);

                if ($user === null) {
                        return [
                                'success' => false,
                                'user' => null,
                                'message' => 'Usuario o contraseña incorrectos...'
                        ];
                }

                // > 2. Verificar la contraseña (lógica de negocio en la entidad)...
                if ($user->verifyPassword($password)) {
                        return [
                                'success' => false,
                                'user' => null,
                                'message' => 'Usuario o contraseña incorrectos...'
                        ];
                }

                // > 3. Retornar éxito...
                return [
                        'success' => true,
                        'user' => $user,
                        'message' => 'Login exitoso'
                ];
        }
}

?>