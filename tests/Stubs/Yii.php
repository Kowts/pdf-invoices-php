<?php

declare(strict_types=1);

use yii\base\Application;

final class Yii
{
    public static Application $app;

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    public static function createObject(string $class): object
    {
        return new $class();
    }

    /**
     * @param array<string, scalar|null> $params
     */
    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        foreach ($params as $key => $value) {
            $message = str_replace($key, (string) $value, $message);
        }

        return $message;
    }
}
