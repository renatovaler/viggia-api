<?php declare(strict_types=1);

namespace App\Auth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

use App\Structure\Http\Controllers\Controller;

use App\Auth\Http\Requests\RegisterUserRequest;
use App\User\Actions\CreateUser\CreateUser;

class RegisteredUserController extends Controller
{

	/**
     * Creates a new user
     *
     * @param App\User\Http\Requests\RegisterUserRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RegisterUserRequest $request): Response
    {
        $user = dispatch_sync(new CreateUser(
            $request->name,
            $request->email,
			$request->password,
			now() //passwordChangedAt
        ));

        event(new Registered($user));

        Auth::loginUsingId($user->id);

        return response()->noContent();
    }
}
