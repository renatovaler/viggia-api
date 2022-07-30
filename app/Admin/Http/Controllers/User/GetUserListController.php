<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\User\UserCollection;

use App\User\Models\User;
use App\User\Actions\GetUserList\GetUserList;

class GetUserListController extends Controller
{
    /**
     * Get list of users
     *
     * @return \App\Admin\Http\Resources\UserCollection
     */
    public function __invoke(): UserCollection
    {
        $this->authorize('viewAny', auth()->user());

        $users = dispatch_sync( new GetUserList() );
        return ( new UserCollection($users) );
    }
}
