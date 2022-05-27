<?php declare(strict_types=1);

namespace App\Domains\User\Http\Auth\Controllers;

use App\Domains\User\Models\User;
use App\Kernel\Http\Controllers\Controller;

class GetAuthenticatedUserProfileInformationController extends Controller
{
    /**
     * Get user profile information
     *
     * @return \App\Domains\User\Models\User
     */
    public function __invoke(): User {}
}
