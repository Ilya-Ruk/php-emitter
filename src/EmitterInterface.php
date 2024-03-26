<?php

declare(strict_types=1);

namespace Rukavishnikov\Php\Emitter;

use Psr\Http\Message\ResponseInterface;

interface EmitterInterface
{
    /**
     * @param ResponseInterface $response
     * @param bool $withoutBody
     * @return void
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false): void;
}
