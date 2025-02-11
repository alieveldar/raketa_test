<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Domain;

    /**
     * Represents a shopping cart.
     *
     * This class encapsulates the cart's UUID, the associated customer, the selected payment method,
     * and the list of items in the cart. It provides methods to add items, retrieve cart details,
     * and serialize or unserialize the cart's data.
     *
     * @package Raketa\BackendTestTask\Domain
     */

    final class Cart
    {
        /**
         * @param string $uuid
         * @param Customer|null $customer
         * @param string $paymentMethod
         * @param array $items
         */
        public function __construct(
            private string $uuid,
            private ?Customer $customer = null,
            private string $paymentMethod,
            private array $items
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
         * @return Customer|null
         */
        public function getCustomer(): ?Customer
        {
            return $this->customer;
        }

        /**
         * @return string
         */
        public function getPaymentMethod(): string
        {
            return $this->paymentMethod;
        }

        /**
         * @return array
         */
        public function getItems(): array
        {
            return $this->items;
        }

        /**
         * @param CartItem $item
         * @return void
         */
        public function addItem(CartItem $item): void
        {
            $this->items[] = $item;
        }

        /**
         * @return array
         */
        public function __serialize(): array
        {
            return [
                'uuid' => $this->uuid,
                'customer' => $this->customer ? json_encode($this->customer) : null,
                'paymentMethod' => $this->paymentMethod,
                'items' => json_encode($this->items),
            ];
        }

        /**
         * @param array $data
         * @return void
         */
        public function __unserialize(array $data): void
        {
            $this->uuid = $data['uuid'];
            $this->customer = $data['customer'] ? json_decode($data['customer'], true) : null;
            $this->paymentMethod = $data['paymentMethod'];
            $this->items = json_decode($data['items'], true);
        }


    }
