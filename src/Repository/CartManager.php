<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Repository;

    use Psr\Log\LoggerInterface;
    use Raketa\BackendTestTask\Domain\Cart;
    use Raketa\BackendTestTask\Infrastructure\ConnectorException;
    use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

    /**
     * Manages the shopping cart data in the application.
     *
     * The `CartManager` class provides functionality to interact with the cart data, including saving
     * and retrieving the cart. It extends `ConnectorFacade`, which provides the underlying connection
     * to Redis for persisting the cart data. This class is responsible for handling cart-related
     * operations, such as storing the cart in Redis and retrieving it based on the current session ID.
     *
     * @package Raketa\BackendTestTask\Repository
     */

    class CartManager extends ConnectorFacade
    {

        /**
         * @param string $host
         * @param int $port
         * @param string|null $password
         * @param int|null $dbIndex
         * @param LoggerInterface $logger
         * @throws \RedisException
         */
        public function __construct(
            string $host = '127.0.0.1',
            int $port = 6379,
            ?string $password = null,
            ?int $dbIndex = null,
            LoggerInterface $logger
        ) {
            parent::__construct($host, $port, $password, 1, $logger);
        }

        /**
         * @inheritdoc
         */
        public function saveCart(Cart $cart)
        {
            $this->connector->set($cart);
        }

        /**
         * @return Cart
         */
        public function getCart(): Cart
        {
            try {
                return $this->connector->get(session_id());
            } catch (ConnectorException $exception) {
                $this->logger->error(sprintf('%s :: %s - %s (%s)', __CLASS__, __METHOD__, $exception->getMessage(),
                    $exception->getTraceAsString()));
            }

            return new Cart(session_id(), null, 'default_payment_method'[]);
        }
    }
