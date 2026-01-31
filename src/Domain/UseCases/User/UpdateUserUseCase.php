<?php

namespace Domain\UseCases\User;

use Domain\DTOs\User\UpdateUserDTO;
use Domain\Repositories\UserRepositoryInterface;

/**
 * ~ Caso de uso: Actualizar un usuario...
 * ~ Recibe el 'user' (identificador de ruta) y un DTO ya validado con los campos a actualizar...
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
         * * Ejecuta la actualización del user.
         *
         * @param string $username user del usuario a actualizar (viene de la ruta).
         * @param UpdateUserDTO $dto Datos ya validados; password y role son opcionales.
         * @return array ['success' => bool, 'message' => string, 'data' => Professor|null]
         */
        public function execute(
                string $username,
                UpdateUserDTO $dto
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
                if (
                        $dto->getPassword() === null &&
                        $dto->getRole() === null
                ) {
                        return [
                                'success' => false,
                                'message' => 'Debe proporcionar al menos un campo para actualizar (password o rol)...',
                                'data' => null
                        ];
                }

                // > 4. Buscar la entidad...
                $user = $this->userRepository->findByUsername($username);

                // > 5. Validaciones y actualizaciones usando las reglas de la entidad...
                try {
                        if ($dto->getPassword() !== null) {
                                $user->updatePassword($dto->getPassword());
                        }
                        if ($dto->getRole() !== null) {
                                $user->updateRole($dto->getRole());
                        }
                } catch (\InvalidArgumentException $e) {
                        // > Muestra los errores de validación con un mensaje amigable...
                        return [
                                'success' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }

                // > 6. Guardar los cambios en el repositorio...
                $updated = $this->userRepository->update($user);

                // > 7. Verificar si la actualización fue exitosa...
                if ($updated) {
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