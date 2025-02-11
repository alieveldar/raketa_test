<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Controller;

    use Psr\Http\Message\RequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Raketa\BackendTestTask\View\ProductsView;

    /**
     * Controller for retrieving products by category.
     *
     * This class handles GET requests to fetch products based on the provided category. It retrieves the products
     * using the ProductsView, formats the product data, and returns it in the response. If no category is provided
     * in the request or if an error occurs, an appropriate error message is returned.
     *
     * @package Raketa\BackendTestTask\Controller
     */

    readonly class GetProductsController
    {
        /**
         * @param ProductsView $productsView
         * @param LoggerInterface $logger
         */
        public function __construct(
            private ProductsView $productsView,
            private LoggerInterface $logger
        ) {
        }

        /**
         * @param RequestInterface $request
         * @return ResponseInterface
         */
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
