<?php declare(strict_types=1);

namespace App\UI\Company\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CompanyBranchCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
