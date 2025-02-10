<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController
{
    public function __construct(
        public CartView $cartView,
        public CartManager $cartManager,
        public LoggerInterface $logger
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        try {

            $cart = $this->cartManager->getCart();

            if (!$cart) {
                $response->getBody()->write(
                    json_encode(
                        [
                            'status' => 'err',
                            'message' => 'Cart not found',
                            'cart' => null,
                        ],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    )
                );

                return $response
                    ->withHeader('Content-Type', 'application/json; charset=utf-8')
                    ->withStatus(404);
            } else {
                $response->getBody()->write(
                    json_encode(
                        [
                            'status' => 'success',
                            'cart' => $this->cartView->toArray($cart),
                        ],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    )
                );
            }
        }catch (\Exception $exception){
            $this->logger->error(sprintf('%s :: %s - %s (%s)', __CLASS__, __METHOD__, $exception->getMessage(), $exception->getTraceAsString()));
            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Internal server error',
                        'cart' => null,
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(500);
        }

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}

