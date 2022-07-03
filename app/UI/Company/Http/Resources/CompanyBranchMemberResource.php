<?php declare(strict_types=1);

namespace App\UI\Company\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyBranchMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'company_branch_id' => $this->company_branch_id
        ];
    }
}
