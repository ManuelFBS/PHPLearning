<?php

namespace Domain\DTOs;

/**
 * ~ Objeto que representa el resultado de una validación....
 * ~ Si es válido, no hay errores. Si es inválido, contiene un array de mensajes por campo...
 */
final class ValidationResult
{
        private bool $valid;
        /** @var array<string, string> Campo => mensaje de error */
        private array $errors;

        private function __construct(bool $valid, array $errors = [])
        {
                $this->valid = $valid;
                $this->errors = $errors;
        }

        public static function success(): self
        {
                return new self(true, []);
        }

        public static function failure(array $errors): self
        {
                return new self(false, $errors);
        }

        public function isValid(): bool
        {
                return $this->valid;
        }

        /**
         * @return array<string, string>
         */
        public function getErrors(): array
        {
                return $this->errors;
        }

        public function getFirstErro(): ?string
        {
                return $this->errors === [] ? null : reset($this->errors);
        }
}

?>