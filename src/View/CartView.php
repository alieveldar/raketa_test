<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\View;

    use Raketa\BackendTestTask\Domain\Cart;
    use Raketa\BackendTestTask\Repository\ProductRepository;

    /**
     * Transforms a Cart object into an array representation.
     *
     * The CartView class is used to convert a Cart entity, including the customer and cart items,
     * into an array format for easier rendering, typically in APIs or views. It also retrieves
     * additional product data from the ProductRepository to provide a full product context for
     * each item in the cart.
     *
     * @package Raketa\BackendTestTask\View
     */

    readonly class CartView
    {
        /**
         * @param ProductRepository $productRepository
         */
        public function __construct(
            private ProductRepository $productRepository
        ) {
        }

        /**
         * @param Cart $cart
         * @return array
         * @throws \Exception
         */
        public function toArray(Cart $cart): array
        {
            $data = [
                'uuid' => $cart->getUuid(),
                'customer' => [
                    'id' => $cart->getCustomer()->getId(),
                    'name' => implode(' ', [
                        $cart->getCustomer()->getLastName(),
                        $cart->getCustomer()->getFirstName(),
                        $cart->getCustomer()->getMiddleName(),
                    ]),
                    'email' => $cart->getCustomer()->getEmail(),
                ],
                'payment_method' => $cart->getPaymentMethod(),
            ];

            $total = 0;
            $data['items'] = [];
            foreach ($cart->getItems() as $item) {
                $itemTotal = $item->getPrice() * $item->getQuantity();
                $total += $itemTotal;
                $product = $this->productRepository->getByUuid($item->getProductUuid());

                $data['items'][] = [
                    'uuid' => $item->getUuid(),
                    'price' => $item->getPrice(),
                    'total' => $itemTotal,
                    'quantity' => $item->getQuantity(),
                    'product' => [
                        'id' => $product->getId(),
                        'uuid' => $product->getUuid(),
                        'name' => $product->getName(),
                        'thumbnail' => $product->getThumbnail(),
                        'price' => $product->getPrice(),
                    ],
                ];
            }

            $data['total'] = $total;

            return $data;
        }
    }
