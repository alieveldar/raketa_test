<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Infrastructure;

    use Redis;
    use RedisException;

    /**
     * A facade for managing Redis connections and operations.
     *
     * The `ConnectorFacade` class is responsible for managing the connection to a Redis server.
     * It acts as a wrapper for the actual Redis connection logic, handling connection parameters such as
     * host, port, password, and database index. It also provides logging for connection failures and successful
     * connections. Once the connection is established, it uses the `Connector` class for interacting with Redis data.
     *
     * This class simplifies the management of Redis connections, making it easier to integrate Redis with other parts
     * of the application.
     *
     * @package Raketa\BackendTestTask\Infrastructure
     */

    class ConnectorFacade
    {
        /**
         * @var string
         */
        public string $host;
        /**
         * @var int
         */
        public int $port = 6379;
        /**
         * @var string|null
         */
        public ?string $password = null;
        /**
         * @var int|null
         */
        public ?int $dbIndex = null;

        /**
         * @var Connector|null
         */
        protected ?Connector $connector = null;

        /**
         * @var LoggerInterface
         */
        public LoggerInterface $logger;

        /**
         * @param string $host
         * @param int $port
         * @param string|null $password
         * @param int|null $dbIndex
         * @param LoggerInterface $logger
         * @throws RedisException
         */
        public function __construct(
            string $host = '127.0.0.1',
            int $port = 6379,
            ?string $password = null,
            ?int $dbIndex = null,
            LoggerInterface $logger
        ) {
            $this->host = $host;
            $this->port = $port;
            $this->password = $password;
            $this->dbIndex = $dbIndex;
            $this->build();
        }

        /**
         * @return void
         * @throws RedisException
         */
        protected function build(): void
        {
            $redis = new Redis();

            try {
                $isConnected = $redis->connect(
                    $this->host,
                    $this->port,
                );
            } catch (RedisException $exception) {
                $this->logger->error(sprintf('%s :: %s - %s (%s)', __CLASS__, __METHOD__, $exception->getMessage(),
                    $exception->getTraceAsString()));
                return;
            }

            if (!$isConnected) {
                $errMessage = sprintf('Unable to connect to Redis at %s:%d', $this->host, $this->port);
                $this->logger->error($errMessage);
                throw new RedisException($errMessage);
            }

            if ($isConnected) {
                if ($this->password !== null) {
                    $redis->auth($this->password);
                }
                if ($this->dbIndex !== null) {
                    $redis->select($this->dbIndex);
                }
                $this->connector = new Connector($redis);
            }
        }
    }
