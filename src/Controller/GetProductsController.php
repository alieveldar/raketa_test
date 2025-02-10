<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Controller;

    use Psr\Http\Message\RequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Raketa\BackendTestTask\View\ProductsView;

    readonly class GetProductsController
    {
        public function __construct(
            private ProductsView $productsView,
            private LoggerInterface $logger
        ) {
        }

        public function get(RequestInterface $request): ResponseInterface
        {
            $response = new JsonResponse();

            try {
                $rawRequest = json_decode($request->getBody()->getContents(), true);
                if (!isset($rawRequest['category'])) {
                    throw new \InvalidArgumentException("Invalid category");
                }
                $response->getBody()->write(
                    json_encode(
                        [
                            'status' => 'success',
                            'products' => $this->productsView->toArray($rawRequest['category'])
                        ],
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    )
                );
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('%s :: %s - %s (%s)', __CLASS__, __METHOD__, $exception->getMessage(),
                    $exception->getTraceAsString()));

                $response->getBody()->write(
                    json_encode(
                        [
                            'status' => 'err',
                            'message' => 'Internal server error',
                            'products' => null,
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
