<?php declare(strict_types=1);

namespace App\UI\Auth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

use App\Structure\Http\Controllers\Controller;

use App\UI\Auth\Http\Requests\RegisterUserRequest;
use App\Domain\User\Actions\CreateUser\CreateUserCommand;

class RegisteredUserController extends Controller
{

	/**
     * Creates a new user
     *
     * @param App\UI\User\Http\Requests\RegisterUserRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RegisterUserRequest $request): Response
    {
        $user = dispatch_sync(new CreateUserCommand(
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
