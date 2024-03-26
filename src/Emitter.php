<?php

declare(strict_types=1);

namespace Rukavishnikov\Php\Emitter;

use Psr\Http\Message\ResponseInterface;

final class Emitter implements EmitterInterface
{
    /**
     * @inheritDoc
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false): void
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

        echo $response->getBody();
    }
}
