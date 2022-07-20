<?php declare(strict_types=1);

namespace App\User\Actions\DeleteUserProfilePhoto;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User\Models\User;
use App\User\Actions\UserDto;
use App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhoto;

final class DeleteUserProfilePhotoHandler
{
    /**
     * Executa a ação
     *
     * @param \App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhoto $command
     * @return \App\User\Actions\UserDto
     */
    public function handle(DeleteUserProfilePhoto $command): UserDto
    {
        try {
			$user = User::findUserByIdOrFail($command->id);
            $previousPhotoPath = $user->profile_photo_path;
            DB::beginTransaction();
                $user->forceFill([
                    'profile_photo_path' => null
                ])->save();
            DB::commit();
            Storage::disk((isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public'))
                    ->delete($previousPhotoPath);
			return UserDto::fromModel($user);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
