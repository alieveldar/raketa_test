<?php

    namespace Raketa\BackendTestTask\View;

    use Raketa\BackendTestTask\Repository\Entity\Product;
    use Raketa\BackendTestTask\Repository\ProductRepository;

    /**
     * Transforms a list of Product entities into an array representation for a specific category.
     *
     * The ProductsView class is used to convert a collection of `Product` entities belonging to a
     * specific category into an array format for easier rendering, typically in APIs or views.
     *
     * @package Raketa\BackendTestTask\View
     */

    readonly class ProductsView
    {
        /**
         * @param ProductRepository $productRepository
         */
        public function __construct(
            private ProductRepository $productRepository
        ) {
        }

        /**
         * @param string $category
         * @return array
         */
        public function toArray(string $category): array
        {
            return array_map(
                fn(Product $product) => [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'category' => $product->getCategory(),
                    'description' => $product->getDescription(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
                $this->productRepository->getByCategory($category)
            );
        }
    }
