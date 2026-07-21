<?php

declare(strict_types=1);

namespace yii\base;

use yii\web\Response;

class Application
{
    public Response $response;

    /** @var array<string, class-string|object> */
    public array $components = [];

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->components);
    }

    /**
     * @param class-string|object $definition
     */
    public function set(string $id, string|object $definition): void
    {
        $this->components[$id] = $definition;
    }
}
