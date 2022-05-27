<?php declare(strict_types=1);

namespace App\Domains\User\Http\Auth\Controllers;

use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use App\Kernel\Http\Controllers\Controller;

class GetAuthenticatedUserProfileInformationController extends Controller
{
    /**
     * Get user profile information
     *
     * @return \App\Domains\User\Models\User
     */
    public function __invoke(Request $request): User
    {
        return $request->user();
    }
}
