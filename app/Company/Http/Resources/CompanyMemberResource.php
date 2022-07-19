<?php declare(strict_types=1);

namespace App\Company\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyMemberResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;
	
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
			'company_id' => $this->company_id,
			'is_company_owner' => ($this->user_id === $this->companyOwner()->owner_user_id),
			//'roles' => $this->companyRoles
        ];
    }
}
