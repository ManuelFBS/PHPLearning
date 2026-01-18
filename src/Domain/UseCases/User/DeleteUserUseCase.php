<?php

namespace Domain\UseCases\User;

use Domain\Repositories\UserRepositoryInterface;

/**
 * ~ Caso de uso: Eliminar un usuario...
 * ~ Contiene toda la lógica de negocio para eliminar usuarios...
 */
class DeleteUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        /**
         * * Ejecutar el caso de uso de eliminación...
         *
         * @param string $username Nombre de usuario a eliminar
         * @return array ['success' => bool, 'message' => string, 'data' => null]
         */
        public function execute(string $username): array
        {
                // > 1. Buscar el usuario por su nombre de usuario...
                $user = $this->userRepository->findByUsername($username);

                // > 2. Validar que el usuario existe...
                if ($user === null) {
                        return [
                                'success' => false,
                                'message' => 'Usuario no encontrado...',
                                'data' => null
                        ];
                }

                // > 3. Intentar eliminar el usuario del repositorio...
                $deleteUser = $this->userRepository->delete($username);

                // > 4. Verificar si la eliminación fue exitosa...
                if ($deleteUser) {
                        return [
                                'success' => true,
                                'message' => 'Usuario eliminado correctamente...',
                                'data' => null
                        ];
                } else {
                        return [
                                'success' => false,
                                'message' => 'No se pudo eliminar el usuario de la base de datos...',
                                'data' => null
                        ];
                }
        }
}

?>