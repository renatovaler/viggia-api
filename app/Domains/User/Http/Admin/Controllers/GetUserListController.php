<?php declare(strict_types=1);

namespace App\Domains\User\Http\Admin\Controllers;

use Illuminate\Http\{Request, JsonResponse};
use App\Kernel\Http\Controllers\Controller;

class GetUserListController extends Controller
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
