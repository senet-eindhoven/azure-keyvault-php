<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Exception;

use Exception;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RequestException extends Exception implements RequestExceptionInterface
{
    public function __construct(
        string $message,
        private RequestInterface $request,
        private ?ResponseInterface $response = null,
        \Throwable $previous = null
    ) {
        // Set the code of the exception if the response is set and not future.
        $code = $response ? $response->getStatusCode() : 0;

        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
