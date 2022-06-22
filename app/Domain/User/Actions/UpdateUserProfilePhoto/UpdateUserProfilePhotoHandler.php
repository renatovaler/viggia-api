<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUserProfilePhoto;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoCommand;

final class UpdateUserProfilePhotoHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(UpdateUserProfilePhotoCommand $command): UserDto
    {
        try {
			$user = User::findUserByIdOrFail($command->id);
            DB::beginTransaction();
                $user->forceFill([
                    'profile_photo_path' => $command->profilePhoto->storePublicly(
                        'profile-photos',
                        ['disk' => (isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public')]
                    ),
                ])->save();
            DB::commit();
			return UserDto::fromModel($user);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
