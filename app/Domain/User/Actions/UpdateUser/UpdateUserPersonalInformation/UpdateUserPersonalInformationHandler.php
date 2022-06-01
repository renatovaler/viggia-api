<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUser\UpdateUserPersonalInformation;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UpdateUser\UserDto;
use App\Domain\User\Actions\UpdateUser\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

final class UpdateUserPersonalInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\UpdateUser\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand $command
     * @return \App\Domain\User\Actions\GetUser\UserDto
     */
    public function handle(UpdateUserPersonalInformationCommand $command): UserDto
    {
        try {
            DB::beginTransaction();

				$user = User::findUserByIdOrFail($command->id);

                $user->forceFill([
                    'name' => $command->name,
                    'email' => $command->email,
                    'email_verified_at' => ($command->email !== $user->email ? null : $user->email_verified_at)
                ])->save();

            DB::commit();

            // Configurar serviço de envio de e-mail antes de ativar isso aqui
            //$user->sendEmailVerificationNotification();

			return UserDto::fromModel($user);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred during our database search.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
