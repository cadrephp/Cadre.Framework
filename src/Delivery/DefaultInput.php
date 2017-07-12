<?php
declare(strict_types=1);

namespace Cadre\Framework\Delivery;

use Dflydev\FigCookies\FigRequestCookies;
use Psr\Http\Message\ServerRequestInterface as Request;

class DefaultInput
{
    public function __invoke(Request $request)
    {
        $sessionId = FigRequestCookies::get($request, 'SESSION_ID');

        return [$sessionId->getValue()];
    }
}
