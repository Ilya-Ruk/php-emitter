<?php

declare(strict_types=1);

namespace Rukavishnikov\Php\Emitter;

use Psr\Http\Message\ResponseInterface;

final class Emitter implements EmitterInterface
{
    /**
     * @inheritDoc
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false, int $bufferLength = 4096): void
    {
        // Status line

        $statusLine = 'HTTP/' . $response->getProtocolVersion();
        $statusLine .= ' ' . $response->getStatusCode();

        if ($response->getReasonPhrase() !== '') {
            $statusLine .= ' ' . $response->getReasonPhrase();
        }

        header($statusLine, true, $response->getStatusCode());

        // Headers

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        // Message body

        if ($withoutBody) {
            return;
        }

        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        while (!$body->eof()) {
            echo $body->read($bufferLength);
        }
    }
}
