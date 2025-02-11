<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Domain;

    /**
     * Represents an item in the shopping cart.
     *
     * This class defines the properties of an individual item in the cart, including the item's UUID,
     * associated product UUID, price, and quantity. It provides methods to access these properties
     * and implements `JsonSerializable` for converting the item into a JSON-compatible format.
     *
     * @package Raketa\BackendTestTask\Domain
     */

    final readonly class CartItem implements \JsonSerializable
    {
        /**
         * @param string $uuid
         * @param string $productUuid
         * @param float $price
         * @param int $quantity
         */
        public function __construct(
            private string $uuid,
            private string $productUuid,
            private float $price,
            private int $quantity,
        ) {
        }

        /**
         * @return string
         */
        public function getUuid(): string
        {
            return $this->uuid;
        }

        /**
         * @return string
         */
        public function getProductUuid(): string
        {
            return $this->productUuid;
        }

        /**
         * @return float
         */
        public function getPrice(): float
        {
            return $this->price;
        }

        /**
         * @return int
         */
        public function getQuantity(): int
        {
            return $this->quantity;
        }

        /**
         * @return array
         */
        public function jsonSerialize(): array
        {
            return [
                'uuid' => $this->uuid,
                'productUuid' => $this->productUuid,
                'price' => $this->price,
                'quantity' => $this->quantity,
            ];
        }
    }
