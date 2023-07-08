<?php

namespace Framework\Classes;

class Data
{
    /**
     * Load data from a JSON file.
     *
     * @param string $jsonPath The path to the JSON file.
     * @return array|null The decoded JSON data as an associative array or null on failure.
     */
    public static function load(string $jsonPath): ?array
    {
        if ($fileContents = file_get_contents($jsonPath)) {
            return json_decode($fileContents, true);
        } else {
            return null;
        }
    }

    /**
     * Save data to a JSON file.
     *
     * @param string $jsonPath The path to the JSON file.
     * @param array $data The data to be saved as an associative array.
     * @return bool True on success, false on failure.
     */
    public static function save(string $jsonPath, array $data): bool
    {
        $result = false;
        array_walk_recursive($data, [self::class, 'convertToNull']);

        if ($jsonData = json_encode($data, JSON_PRETTY_PRINT)) {
            $jsonData = preg_replace_callback('/^ +/m', function($m) {
                return str_repeat(' ', strlen($m[0]) / 2);
            }, $jsonData);
            $result = file_put_contents($jsonPath, $jsonData);
        }

        return $result;
    }

    /**
     * Convert empty strings to null in an array recursively.
     *
     * @param mixed $item The item to be converted.
     */
    protected static function convertToNull(&$item): void
    {
        if ($item == '') {
            $item = null;
        } elseif (is_array($item)) {
            array_walk_recursive($item, [self::class, 'convertToNull']);
        }
    }
}
