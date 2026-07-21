<?php

declare(strict_types=1);

namespace yii\web;

class Response
{
    /**
     * @param array<string, mixed> $options
     */
    public function sendContentAsFile(string $content, string $attachmentName, array $options = []): self
    {
        return $this;
    }
}
