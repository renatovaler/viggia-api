<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Requests\AddCompanyMemberRequest;
use App\Domain\Company\Actions\AddCompanyMember\AddCompanyMemberCommand;

class AddCompanyMemberController extends Controller
{
    /**
     * Add member to the company
     *
     * @param App\UI\Company\Http\Requests\AddCompanyMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(AddCompanyMemberRequest $request): JsonResponse
    {
        dispatch_sync(
            new AddCompanyMemberCommand(
				$request->input('company_id'),
				$request->input('user_id'),
			)
        );
		return api()->success([
			'message' => __('Success! The user has been added as a member of this company.')
		], 200);
    }
}
