<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Infrastructure;

    use Raketa\BackendTestTask\Domain\Cart;
    use Redis;
    use RedisException;

    /**
     * A class responsible for interacting with Redis.
     *
     * This class provides methods to get, set, and check the existence of data in Redis.
     * It serializes and unserializes the `Cart` object when storing and retrieving it.
     * The methods handle Redis-related exceptions and throw custom `ConnectorException` in case of errors.
     *
     * @package Raketa\BackendTestTask\Infrastructure
     */

    class Connector
    {
        /**
         * @var Redis
         */
        private Redis $redis;

        /**
         * @param Redis $redis
         */
        public function __construct(Redis $redis)
        {
            $this->redis = $redis;
        }

        /**
         * @throws ConnectorException
         */
        public function get(string $key)
        {
            try {
                return unserialize($this->redis->get($key));
            } catch (RedisException $e) {
                throw new ConnectorException('Connector error', $e->getCode(), $e);
            }
        }

        /**
         * @throws ConnectorException
         */
        public function set(Cart $cart)
        {
            try {
                $this->redis->setex($cart->getUuid(), 24 * 60 * 60, serialize($cart));
            } catch (RedisException $e) {
                throw new ConnectorException('Connector error', $e->getCode(), $e);
            }
        }

        /**
         * @param string $key
         * @return bool
         * @throws RedisException
         */
        public function has(string $key): bool
        {
            return (bool)$this->redis->exists($key);
        }
    }
