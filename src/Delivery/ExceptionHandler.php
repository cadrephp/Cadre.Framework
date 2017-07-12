<?php
declare(strict_types=1);

namespace Cadre\Framework\Delivery;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ExceptionHandler
{
    protected $exceptionResponse;

    public function __construct(Response $exceptionResponse)
    {
        $this->exceptionResponse = $exceptionResponse;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            $response = $next($request, $response);
        } catch (Exception $e) {
            $response = $this->exceptionResponse->withStatus(500);
            if (isset($e->xdebug_message)) {
                $response->getBody()->write(
                    '<table class="xdebug-error xe-parse-error" dir="ltr" border="1" cellspacing="0" cellpadding="1">' .
                    $e->xdebug_message .
                    '</table>'
                );
            } else {
                $response->getBody()->write(get_class($e) . ': ' . $e->getMessage());
            }
        }
        return $response;
    }
}
