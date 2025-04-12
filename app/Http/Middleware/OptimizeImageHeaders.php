<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptimizeImageHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Chỉ áp dụng cho các request đến thư mục productImages
        if ($request->is('productImages/*')) {
            $response->header('Cache-Control', 'public, max-age=86400');
            $response->header('Connection', 'keep-alive');
            $response->header('Accept-Ranges', 'bytes');
        }
        
        return $response;
    }
}