<?php

namespace Framework\Classes;

use Framework\Enums\HttpMethod;

class Route
{
    /**
     * @var array The registered route handlers.
     */
    protected static $handlers;

    /**
     * @var callable|array The handler for routes not found.
     */
    protected static $notFoundHandler;

    /**
     * Register a GET route.
     *
     * @param string $path The route path.
     * @param callable|array $callback The callback function or method.
     */
    public static function get(string $path, callable | array $callback): void
    {
        self::addHandler(HttpMethod::GET, $path, $callback);
    }

    /**
     * Register a POST route.
     *
     * @param string $path The route path.
     * @param callable|array $callback The callback function or method.
     */
    public static function post(string $path, callable | array $callback): void
    {
        self::addHandler(HttpMethod::POST, $path, $callback);
    }

    /**
     * Register a DELETE route.
     *
     * @param string $path The route path.
     * @param callable|array $callback The callback function or method.
     */
    public static function delete(string $path, callable | array $callback): void
    {
        self::addHandler(HttpMethod::DELETE, $path, $callback);
    }

    /**
     * Register a PUT route.
     *
     * @param string $path The route path.
     * @param callable|array $callback The callback function or method.
     */
    public static function put(string $path, callable | array $callback): void
    {
        self::addHandler(HttpMethod::PUT, $path, $callback);
    }

    /**
     * Register the handler for routes not found.
     *
     * @param callable|array $callback The callback function or method.
     */
    public static function addNotFoundHandler(callable | array $callback): void
    {
        self::$notFoundHandler = $callback;
    }

    /**
     * Run the router and handle the current request.
     *
     * @param Request $request The request object.
     * @return Response The response object.
     */
    public static function run(Request $request): Response
    {
        $params = ['request' => $request];
        $callback = null;
        $isNotFound = false;

        if (isset(self::$handlers[$request->method])) {
            foreach (self::$handlers[$request->method] as $handler) {
                $pattern = self::convertPathToRegex($handler['path']);

                if (preg_match($pattern, $request->path, $matches)) {
                    $auxParams = array_filter($matches, function ($key) {
                        return is_string($key);
                    }, ARRAY_FILTER_USE_KEY);
                    $params = array_merge($params, $auxParams);
                    $callback = $handler['callback'];
                    break;
                }
            }
        }

        if (!$callback) {
            $isNotFound = true;

            if (!empty(self::$notFoundHandler)) {
                $callback = self::$notFoundHandler;
            }
        }

        if (is_callable($callback)) {
            $response = call_user_func_array($callback, $params);
        } elseif ($callback) {
            [$class, $method] = $callback;
            $object = new $class;
            $response = call_user_func_array([$object, $method], $params);
        } else {
            $response = Response::make();
        }

        if ($isNotFound) {
            $response->notFound();
        }

        return $response;
    }

    /**
     * Add a route handler to the handlers array.
     *
     * @param HttpMethod $method The request method.
     * @param string $path The route path.
     * @param callable|array $callback The callback function or method.
     */
    protected static function addHandler(HttpMethod $method, string $path, callable | array $callback): void
    {
        self::$handlers[$method->value][] = [
            'path' => $path,
            'callback' => $callback,
        ];
    }

    /**
     * Convert a route path to a regular expression pattern.
     *
     * @param string $path The route path.
     * @return string The converted regular expression pattern.
     */
    protected static function convertPathToRegex(string $path): string
    {
        $path = preg_quote($path, '/');
        $path = str_replace(['\{', '\}'], ['(?P<', '>[^/]+)'], $path);
        return '#^' . $path . '$#i';
    }
}
