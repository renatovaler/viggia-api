<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\User;


use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password as PasswordRules;

use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Resources\User\UserResource;
use App\UI\Admin\Http\Requests\User\CreateUserRequest;

use App\Domain\User\Actions\CreateUser\CreateUserCommand;

class CreateUserController extends Controller
{    

	/**
     * Creates a new user
     *
     * @param App\UI\Admin\Http\Requests\User\CreateUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $user = dispatch_sync(new CreateUserCommand(
            $request->name,
            $request->email,
			$request->password
        ));
		
        event( new Registered($user) );
		
        return ( new UserResource($user) )->response($request);
    }
}
