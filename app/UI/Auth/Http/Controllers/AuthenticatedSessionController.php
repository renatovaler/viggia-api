<?php declare(strict_types=1);

namespace App\UI\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\UI\Auth\Http\Requests\LoginRequest;
use App\Structure\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\UI\Auth\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

		/*
		return response()->json([
			'code' => 201,
			'type' => 'success',
			'message' => __('Session started successfully.')
		], 201);
		*/
		
        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

		/*
		return response()->json([
			'code' => 200,
			'type' => 'success',
			'message' => __('Session ended successfully.')
		], 200);
		*/
		
        return response()->noContent();
    }
}
