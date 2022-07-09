<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUserPersonalInformation;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

final class UpdateUserPersonalInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand $command
     * @return \App\Domain\User\Actions\UserDto
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
			$user->sendEmailVerificationNotification();
			return UserDto::fromModel($user);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
