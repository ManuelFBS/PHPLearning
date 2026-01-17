<?php

namespace Presentation\Controllers;

use Domain\UseCases\Auth\LoginUseCase;

/**
 * ~ Controlador de autenticación...
 * ~ Se encarga de recibir las peticiones HTTP y llamar a los casos de uso...
 */
class AuthController
{
        private LoginUseCase $loginUseCase;

        public function __construct(LoginUseCase $loginUseCase)
        {
                $this->loginUseCase = $loginUseCase;
        }

        // * Procesar el login...
        public function login(string $username, $password): array
        {
                // > Validación básica de entrada...
                if (empty($username) || empty($password)) {
                        return [
                                'success' => false,
                                'message' => 'Usuario y contraseña son requeridos...'
                        ];
                }

                // > Llamar al caso de uso...
                $result = $this->loginUseCase->execute($username, $password);

                // > Si es exitoso crear la sesión...
                if ($result['success'] && $result['user'] !== null) {
                        $user = $result['user'];
                        $_SESSION['user_id'] = $user->getDni();
                        $_SESSION['user_dni'] = $user->getDni();
                        $_SESSION['user_name'] = $user->getUsername();
                        $_SESSION['user_role'] = $user->getRole();
                }

                return $result;
        }
}

?>