<?php

    declare(strict_types=1);

    namespace Raketa\BackendTestTask\Infrastructure;

    /**
     * Custom exception class for handling errors related to Redis operations.
     *
     * This class implements the `Throwable` interface and serves as a custom exception
     * to handle errors that occur during interactions with Redis. It allows capturing
     * detailed information about the error, including the message, code, file, line, and trace.
     * This exception can be used to provide more specific error information when handling Redis-related failures.
     *
     * @package Raketa\BackendTestTask\Infrastructure
     */

    class ConnectorException implements \Throwable
    {
        /**
         * @param string $message
         * @param int $code
         * @param \Throwable|null $previous
         */
        public function __construct(
            private string $message,
            private int $code,
            private ?\Throwable $previous,
        ) {
        }

        /**
         * @return string
         */
        public function getMessage(): string
        {
            return $this->message;
        }

        /**
         * @return int
         */
        public function getCode(): int
        {
            return $this->code;
        }

        /**
         * @return string
         */
        public function getFile(): string
        {
            return $this->previous->getFile();
        }

        /**
         * @return int
         */
        public function getLine(): int
        {
            return $this->previous->getLine();
        }

        /**
         * @return array
         */
        public function getTrace(): array
        {
            return $this->previous->getTrace();
        }

        /**
         * @return string
         */
        public function getTraceAsString(): string
        {
            return $this->previous->getTraceAsString();
        }

        /**
         * @return \Throwable|null
         */
        public function getPrevious(): ?\Throwable
        {
            return $this->previous;
        }

        /**
         * @return string
         */
        public function __toString(): string
        {
            return sprintf(
                '[%s] %s in %s on line %d',
                $this->getCode(),
                $this->getMessage(),
                $this->getFile(),
                $this->getLine(),
            );
        }
    }
