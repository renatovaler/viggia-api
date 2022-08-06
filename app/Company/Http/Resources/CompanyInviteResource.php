<?php declare(strict_types=1);

namespace App\Company\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyInviteResource extends JsonResource
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
			'token' => $this->token,
			'company_id' => $this->companyId,
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'name' => $this->user->name
            ],
            'roles' => $this->roles,
            'expired' => $this->expired,
            'expired_at' => $this->expired_at
        ];
    }
}
