<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Controller;

    use Psr\Http\Message\RequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Raketa\BackendTestTask\Domain\CartItem;
    use Raketa\BackendTestTask\Repository\CartManager;
    use Raketa\BackendTestTask\Repository\ProductRepository;
    use Raketa\BackendTestTask\View\CartView;
    use Ramsey\Uuid\Uuid;

    /**
     * Controller for adding a product to the cart.
     *
     * This class handles POST requests to add a product to the cart. It extracts data from the request,
     * validates it, retrieves the product by its UUID, adds the item to the cart, and saves the updated cart.
     * In case of an error, it returns an appropriate error response with a corresponding status code.
     *
     * @package Raketa\BackendTestTask\Controller
     */

    readonly class AddToCartController
    {
        /**
         * @param ProductRepository $productRepository
         * @param CartView $cartView
         * @param CartManager $cartManager
         * @param LoggerInterface $logger
         */
        public function __construct(
            private ProductRepository $productRepository,
            private CartView $cartView,
            private CartManager $cartManager,
            private LoggerInterface $logger
        ) {
        }

        /**
         * @param RequestInterface $request
         * @return ResponseInterface
         */
        public function post(RequestInterface $request): ResponseInterface
        {
            $response = new JsonResponse();
            $responseCode = 200;
            try {
                $rawRequest = json_decode($request->getBody()->getContents(), true);

                if (!isset($rawRequest['productUuid']) || !isset($rawRequest['quantity'])) {
                    throw new \HttpInvalidParamException('Invalid params');
                }

                $product = $this->productRepository->getByUuid($rawRequest['productUuid']);
                $cart = $this->cartManager->getCart();

                if (!$cart) {
                    $response->getBody()->write(
                        json_encode(
                            [
                                'status' => 'err',
                                'msg' => 'Cart not found',
                                'cart' => null,
                            ],
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                        )
                    );
                    $responseCode = 404;
                    return $response
                        ->withHeader('Content-Type', 'application/json; charset=utf-8')
                        ->withStatus($responseCode);
                }
                $cart->addItem(new CartItem(
                    Uuid::uuid4()->toString(),
                    $product->getUuid(),
                    $product->getPrice(),
                    $rawRequest['quantity'],
                ));
                $this->cartManager->saveCart($cart);
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('%s :: %s - %s (%s)', __CLASS__, __METHOD__, $exception->getMessage(),
                    $exception->getTraceAsString()));
                $response->getBody()->write(
                    json_encode(
                        [
                            'status' => 'err',
                            'message' => 'Internal server error',
                            'cart' => null,
                        ],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    )
                );
                $responseCode = 500;
                return $response
                    ->withHeader('Content-Type', 'application/json; charset=utf-8')
                    ->withStatus($responseCode);
            }

            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'success',
                        'cart' => $this->cartView->toArray($cart)
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus($responseCode);
        }
    }
