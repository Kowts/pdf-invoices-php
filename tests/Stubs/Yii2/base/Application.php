<?php

declare(strict_types=1);

namespace yii\base;

use yii\web\Response;

class Application
{
    public Response $response;

    public function has(string $id): bool
    {
        return false;
    }

    /**
     * @param class-string|object $definition
     */
    public function set(string $id, string|object $definition): void
    {
    }
}
