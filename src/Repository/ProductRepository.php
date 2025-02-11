<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Repository;

    use Doctrine\DBAL\Connection;
    use Raketa\BackendTestTask\Repository\Entity\Product;

    /**
     * Handles operations related to the "products" table in the database.
     *
     * The `ProductRepository` class is responsible for fetching product data from the database.
     * It includes methods for retrieving products by UUID, category, and for constructing
     * `Product` entities from database rows. It relies on the Doctrine DBAL connection to
     * perform queries to the database.
     *
     * @package Raketa\BackendTestTask\Repository
     */

    class ProductRepository
    {
        /**
         * @var Connection
         */
        private Connection $connection;

        /**
         * @param Connection $connection
         */
        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

        /**
         * @param string $uuid
         * @return Product
         * @throws \Exception
         */
        public function getByUuid(string $uuid): Product
        {
            $row = $this->connection->fetchOne(
                "SELECT * FROM products WHERE uuid = :uuid", ['uuid' => $uuid]
            );

            if (empty($row)) {
                throw new \Exception('Product not found');
            }

            return $this->make($row);
        }

        /**
         * @param string $category
         * @return array
         */
        public function getByCategory(string $category): array
        {
            return array_map(
                static fn(array $row): Product => $this->make($row),
                $this->connection->fetchAllAssociative(
                    "SELECT id, uuid, is_active, category, name, description, thumbnail, price
                FROM products WHERE is_active = 1 AND category = :category", ['category' => $category],
                )
            );
        }

        /**
         * @param array $row
         * @return Product
         */
        public function make(array $row): Product
        {
            return new Product(
                $row['id'],
                $row['uuid'],
                $row['is_active'],
                $row['category'],
                $row['name'],
                $row['description'],
                $row['thumbnail'],
                $row['price'],
            );
        }
    }
