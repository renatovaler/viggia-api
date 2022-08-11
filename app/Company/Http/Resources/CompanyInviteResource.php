<?php declare(strict_types=1);

namespace App\Company\Http\Resources;

use Carbon\Carbon;
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
			'company_id' => $this->company_id,
            'user' => $this->whenLoaded('invitedUser', [
                'id' => $this->invitedUser->id,
                'email' => $this->invitedUser->email,
                'name' => $this->invitedUser->name
            ]),
            'roles' => $this->roles,
            'expired' => (Carbon::parse($this->expires_in) < Carbon::now()),
            'expires_in' => $this->expires_in
        ];
    }
}
