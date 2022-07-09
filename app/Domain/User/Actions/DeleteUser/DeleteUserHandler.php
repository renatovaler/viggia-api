<?php declare(strict_types=1);

namespace App\Domain\User\Actions\DeleteUser;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\DeleteUser\DeleteUserCommand;

final class DeleteUserHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\DeleteUser\DeleteUserCommand $command
     * @return void
     */
    public function handle(DeleteUserCommand $command): void
    {
        try {
            DB::beginTransaction();
				User::deleteUser($command->id);
            DB::commit();
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
