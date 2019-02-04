<?php
namespace App\Http\Middleware;

use App\Utils\JsonFormat;
use Closure;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ApiResponse
{
    /**
     *- Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);
        $original = $response->getOriginalContent();
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return $response;
        } else {
            $original = JsonFormat::success($original);
            $response->setContent($original->getContent());
            return $response;
        }

    }
}
