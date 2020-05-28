<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'api_admin')
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        $this->checkForToken($request);

        // 使用 try 包裹，以捕捉 token 过期所抛出的 TokenExpiredException  异常
        try {
            Auth::shouldUse($guard);
            // 检测用户是否已经登录, 同时判断当前用户的分组是否一致
            // 后台用户不允许登录前台, 前台用户不允许登录后台
            if (auth($guard)->user() && Auth::payload()->get('guard') == $guard) {
                return $next($request);
            }
            throw new UnauthorizedHttpException('jwt-auth', '未登录');
        } catch (TokenExpiredException $exception) {
//             此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
            try {
//                 刷新用户的 token
                $token = $this->auth->refresh();
//                 使用一次性登录以保证此次请求的成功
                Auth::guard($guard)
                    ->onceUsingId(
                        $this->auth->manager()
                            ->getPayloadFactory()
                            ->buildClaimsCollection()
                            ->toPlainArray()['sub']
                    );
            } catch (JWTException $exception) {
//                 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
            }
        }

        return $next($request)->withHeaders([
            'Authorization'=> 'Bearer '.$token,
        ]);
    }
}
