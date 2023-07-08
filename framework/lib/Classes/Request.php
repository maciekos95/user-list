<?php

namespace Framework\Classes;

class Request
{
    /**
    * @var string The path of the request.
    */
    public $path;

    /**
     * @var string The HTTP method of the request.
     */
    public $method;

    /**
     * @var array The parameters of the request.
     */
    public $params;

    /**
     * The Request constructor.
     */
    protected function __construct()
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = $_REQUEST;
    }

    /**
     * Create a new Request instance.
     *
     * @return static The created instance.
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Get the value of the specified input parameter.
     *
     * @param string $key The key of the input parameter.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The value of the input parameter if found, the default value otherwise.
     */
    public function input(string $key, $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Get all the parameters of the request.
     *
     * @return array The array of request parameters.
     */
    public function all(): array
    {
        return $this->params;
    }

    /**
     * Get only the specified parameters from the request.
     *
     * @param array $keys The keys of the parameters to retrieve.
     * @return array The filtered array of parameters.
     */
    public function only(array $keys): array
    {
        $filteredParams = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $this->params)) {
                $filteredParams[$key] = $this->params[$key];
            }
        }

        return $filteredParams;
    }

    /**
     * Check if the request has the specified parameter.
     *
     * @param string $key The key of the parameter to check.
     * @return bool True if the parameter exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->params);
    }
}
