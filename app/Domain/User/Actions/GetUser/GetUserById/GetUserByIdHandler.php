<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetUser\GetUserById;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand;

final class GetUserByIdHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(GetUserByIdCommand $command): UserDto
    {
        try {
            $user = User::findUserByIdOrFail($command->id);
            return UserDto::fromModel($user);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 403);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
