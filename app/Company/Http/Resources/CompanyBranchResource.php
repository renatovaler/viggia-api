<?php declare(strict_types=1);

namespace App\Company\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyBranchResource extends JsonResource
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
			'owner_company_id' => $this->owner_company_id,
			'name' => $this->name
        ];
    }
}
