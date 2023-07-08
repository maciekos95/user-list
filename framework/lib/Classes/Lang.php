<?php

namespace Framework\Classes;

class Lang
{
    /**
     * Get the translation for the given text key from the specified language file and section.
     *
     * @param string $key The key representing the full path to the message.
     * @param array $args Arguments to replace in the message.
     * @return string The translated message or the original key with replacements if no translation is found.
     */
    public static function get(string $key, array $args = []): string
    {
        $language = $_COOKIE['language'] ?? 'en';

        if (!strpos($key, '.')) {
            $message = $key;
        } else {
            if (strpos($key, '::')) {
                [$path, $key] = explode('::', $key, 2);
                $path = str_replace('.', '/', $path);
            }

            [$file, $key] = explode('.', $key, 2);
            $langArrayPath = "lang/$language/$file.php";

            if (isset($path)) {
                $langArrayPath = "$path/$langArrayPath";
            }

            if (!file_exists($langArrayPath)) {
                $message = $key;
            } else {
                $message = require $langArrayPath;
                $keys = explode('.', $key);

                foreach ($keys as $key) {
                    if (isset($message[$key])) {
                        $message = $message[$key];
                    } else {
                        $message = $key;
                        break;
                    }
                }
            }
        }

        if (!empty($args)) {
            foreach ($args as $key => $value) {
                if (is_string($value)) {
                    $value = self::get($value);
                }

                $placeholder = ':' . $key;
                $message = str_replace($placeholder, $value, $message);
            }
        }

        return $message;
    }

    /**
     * Set the language cookie.
     *
     * @param string $language The language code.
     * @return bool True on success, false on failure.
     */
    public static function set(string $language): bool
    {
        if ($language) {
            setcookie('language', $language, time() + 86400 * 30, '/');
            $_COOKIE['language'] = $language;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all available languages as an array.
     * 
     * @return array The array of available languages.
     */
    public static function list(): array
    {
        $availableLanguages = array_diff(scandir('lang'), ['.', '..']);

        foreach ($availableLanguages as $language) {
            $languages[] = basename($language);
        }

        return $languages;
    }
}