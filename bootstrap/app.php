<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'logout',
            '*/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->is('logout') || $request->is('*/logout') || $request->routeIs('logout')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('info', 'You have been logged out.');
            }
            if ($request->is('reset-password') || $request->is('forgot-password')) {
                return redirect()->back()->withInput($request->except('password', 'password_confirmation', '_token'))->withErrors(['email' => 'Your session expired. Please submit the form again.']);
            }

            return redirect()->route('login')->with('error', 'Your session expired. Please log in again.');
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->is('logout') || $request->is('*/logout') || $request->routeIs('logout')) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('info', 'You have been logged out.');
                }

                return redirect()->route('login')->with('error', 'Your session expired. Please log in again.');
            }
        });
    })->create();
