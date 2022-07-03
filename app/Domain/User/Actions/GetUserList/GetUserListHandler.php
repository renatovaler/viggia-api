<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetUserList;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserListDto;

final class GetUserListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
        try {
            $users = User::all();
            return UserListDto::fromCollection($users);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
