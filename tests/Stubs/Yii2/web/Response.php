<?php

declare(strict_types=1);

namespace yii\web;

class Response
{
    public ?string $content = null;

    public ?string $attachmentName = null;

    /** @var array<string, mixed> */
    public array $options = [];

    /**
     * @param array<string, mixed> $options
     */
    public function sendContentAsFile(string $content, string $attachmentName, array $options = []): self
    {
        $this->content = $content;
        $this->attachmentName = $attachmentName;
        $this->options = $options;

        return $this;
    }
}
