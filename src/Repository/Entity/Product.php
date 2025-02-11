<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Repository\Entity;

    /**
     * Represents a product in the system.
     *
     * The `Product` class encapsulates the attributes and behaviors associated with a product.
     * It stores data such as the product's ID, unique identifier (UUID), active status, category,
     * name, description, thumbnail image URL, and price. The class provides getter methods
     * for each attribute, enabling access to the product details.
     *
     * This class is useful for managing product-related data in the application, whether
     * it's being displayed in a catalog, added to a shopping cart, or handled by an inventory system.
     *
     * @package Raketa\BackendTestTask\Repository\Entity
     */

    readonly class Product
    {
        /**
         * @param int $id
         * @param string $uuid
         * @param bool $isActive
         * @param string $category
         * @param string $name
         * @param string $description
         * @param string $thumbnail
         * @param float $price
         */
        public function __construct(
            private int $id,
            private string $uuid,
            private bool $isActive,
            private string $category,
            private string $name,
            private string $description,
            private string $thumbnail,
            private float $price
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
        public function getUuid(): string
        {
            return $this->uuid;
        }

        /**
         * @return bool
         */
        public function isActive(): bool
        {
            return $this->isActive;
        }

        /**
         * @return string
         */
        public function getCategory(): string
        {
            return $this->category;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getDescription(): string
        {
            return $this->description;
        }

        /**
         * @return string
         */
        public function getThumbnail(): string
        {
            return $this->thumbnail;
        }

        /**
         * @return float
         */
        public function getPrice(): float
        {
            return $this->price;
        }
    }
