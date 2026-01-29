<?php

namespace Domain\UseCases\User;

use Domain\Repositories\UserRepositoryInterface;

/**
 * ~ Caso de uso: Actualizar un usuario...
 * ~ Contiene toda la lógica de negocio para actualizar usuarios...
 * ~ Cambios de contraseña o del rol de un usuario (únicamente)...
 */
class UpdateUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        /**
         * * Ejecutar el caso de uso de actualización...
         *
         * @param string $username Nombre de usuario a actualizar
         * @param string|null $newPassword Nueva contraseña (opcional)
         * @param string|null $newRole Nuevo rol (opcional)
         * @return array ['success' => bool, 'message' => string, 'data' => User|null]
         */
        public function execute(
                string $username,
                ?string $newPassword = null,
                ?string $newRole = null
        ): array {
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

                // > 3. Validar que al menos un campo se va a actualizar...
                if ($newPassword === null && $newRole === null) {
                        return [
                                'success' => false,
                                'message' => 'Debe proporcionar al menos un campo para actualizar (password o rol)...',
                                'data' => null
                        ];
                }

                // > 4. Intentar actualizar la contraseña si se proporciona...
                if ($newPassword !== null) {
                        try {
                                // Se usa el método de negocio de la entidad que ya valida la contraseña...
                                $user->updatePassword($newPassword);
                        } catch (\InvalidArgumentException $e) {
                                return [
                                        'success' => false,
                                        'message' => $e->getMessage(),
                                        'data' => null
                                ];
                        }
                }

                // > 5. Intentar actualizar el rol si se proporciona...
                if ($newRole !== null) {
                        try {
                                // Se usa el método de negocio de la entidad que ya valida el rol...
                                $user->updateRole($newRole);
                        } catch (\InvalidArgumentException $e) {
                                // Si la validación falla, retornamos el error...
                                return [
                                        'success' => false,
                                        'message' => $e->getMessage(),
                                        'data' => null
                                ];
                        }
                }

                // > 6. Guardar los cambios en el repositorio...
                $updateResult = $this->userRepository->update($user);

                // > 7. Verificar si la actualización fue exitosa...
                if ($updateResult) {
                        return [
                                'success' => true,
                                'message' => 'Usuario actualizado correctamente...',
                                'data' => $user
                        ];
                } else {
                        return [
                                'success' => true,
                                'message' => 'No se pudo actualizar el usuario en la base de datos...',
                                'data' => null
                        ];
                }
        }
}

?>