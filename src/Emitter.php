<?php

declare(strict_types=1);

namespace Rukavishnikov\Php\Emitter;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class Emitter implements EmitterInterface
{
    /**
     * @inheritDoc
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false, int $bufferLength = 4096): void
    {
        if (headers_sent()) {
            throw new RuntimeException('HTTP headers have already been sent!');
        }

        // Status line

        $statusLine = 'HTTP/' . $response->getProtocolVersion();
        $statusLine .= ' ' . $response->getStatusCode();

        if (!empty($response->getReasonPhrase())) {
            $statusLine .= ' ' . $response->getReasonPhrase();
        }

        header($statusLine, true, $response->getStatusCode());

        // Headers

        foreach ($response->getHeaders() as $name => $values) {
            $nameNormalized = str_replace('-', ' ', $name);
            $nameNormalized = ucwords($nameNormalized);
            $nameNormalized = str_replace(' ', '-', $nameNormalized);

            foreach ($values as $value) {
                header(sprintf("%s: %s", $nameNormalized, $value), false);
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
