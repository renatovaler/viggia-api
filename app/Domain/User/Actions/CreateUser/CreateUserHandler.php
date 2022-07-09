<?php declare(strict_types=1);

namespace App\Domain\User\Actions\CreateUser;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\CreateUser\CreateUserCommand;

final class CreateUserHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\CreateUser\CreateUserCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(CreateUserCommand $command): UserDto
    {
        try {
            DB::beginTransaction();
				// Create user
				$user = User::forceCreate([
					'name' => $command->name,
					'email' => $command->email,
					'password' => Hash::make($command->password),
                    'password_changed_at' => $command->passwordChangedAt
				]);
				// Attach default system role
				$user->addRoleToUserByName('common_user');
				$user->save();
            DB::commit();
			return UserDto::fromModel($user);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
