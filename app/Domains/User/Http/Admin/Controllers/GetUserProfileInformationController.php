<?php declare(strict_types=1);

namespace App\Domains\User\Http\Admin\Controllers;

use App\Domains\User\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Validation\ValidationException;

use App\Kernel\Http\Controllers\Controller;

class GetUserProfileInformationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}
