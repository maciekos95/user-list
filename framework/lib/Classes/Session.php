<?php

namespace Framework\Classes;

use Framework\Enums\MessageType;

class Session
{
    /**
     * Start the session.
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a value for the given key in the session.
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to be set.
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Set a message in the session.
     *
     * @param MessageType $type The type of the message.
     * @param string $content The content of the message.
     * @param string|null $key The key to store the message under.
     */
    public static function setMessage(MessageType $type, string $content, string $key = null): void
    {
        self::start();
        $_SESSION['messages'][$key] = [
            'type' => $type,
            'content' => $content,
        ];
    }

    /**
     * Retrieve the value for the given key from the session.
     *
     * @param string $key The key to retrieve the value for.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The value associated with the key if exists, default value or null otherwise.
     */
    public static function get(string $key, $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Retrieve a specific message from the session.
     *
     * @param string $key The key of the message to retrieve.
     * @return mixed The message associated with the key if exists, null otherwise.
     */
    public static function getMessage(string $key): mixed
    {
        self::start();
        return $_SESSION['messages'][$key] ?? null;
    }

    /**
     * Retrieve all messages from the session.
     *
     * @return array The array of messages stored in the session.
     */
    public static function getMessages(): array
    {
        self::start();
        return $_SESSION['messages'] ?? [];
    }

    /**
     * Check if the given key exists in the session.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists, false otherwise.
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove the value for the given key from the session.
     *
     * @param string $key The key to remove.
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Remove the specific message from the session.
     *
     * @param string $key The key of the message to remove.
     */
    public static function removeMessage(string $key): void
    {
        self::start();
        unset($_SESSION['messages'][$key]);
    }

    /**
     * Remove all messages from the session.
     */
    public static function removeMessages(): void
    {
        self::start();
        unset($_SESSION['messages']);
    }

    /**
     * Clear all data from the session.
     */
    public static function clear(): void
    {
        self::start();
        session_unset();
    }

    /**
     * Close the session.
     */
    public static function close(): void
    {
        self::start();
        session_write_close();
    }
}
