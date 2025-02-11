<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Domain;

    /**
     * Represents a customer.
     *
     * This class defines the properties of a customer, including the customer's ID, first name,
     * last name, middle name, and email address. It provides methods to access these properties
     * and implements `JsonSerializable` to allow the customer to be serialized into a JSON-compatible format.
     *
     * @package Raketa\BackendTestTask\Domain
     */

    final readonly class Customer implements \JsonSerializable
    {
        /**
         * @param int $id
         * @param string $firstName
         * @param string $lastName
         * @param string $middleName
         * @param string $email
         */
        public function __construct(
            private int $id,
            private string $firstName,
            private string $lastName,
            private string $middleName,
            private string $email,
        ) {
        }

        /**
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getFirstName(): string
        {
            return $this->firstName;
        }

        /**
         * @return string
         */
        public function getLastName(): string
        {
            return $this->lastName;
        }

        /**
         * @return string
         */
        public function getMiddleName(): string
        {
            return $this->middleName;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }

        /**
         * @return array
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'middleName' => $this->middleName,
                'email' => $this->email,
            ];
        }
    }
