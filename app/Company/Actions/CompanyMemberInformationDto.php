<?php declare(strict_types=1);

namespace App\Company\Actions;

use App\Company\Models\Company;
use App\Company\Models\CompanyMember;

final class CompanyMemberInformationDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly int $userId
     * @param readonly int $companyId
     * @param readonly array $roles
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly int $companyId,
        public readonly bool $isCompanyOwner,
        //public readonly array $roles
    ) {}
	
    public static function fromModel(CompanyMember $companyMember): self
    {
        return new self(
            $companyMember->id,
			$companyMember->user_id,
            $companyMember->company_id,
            $companyMember->user_id === $companyMember->company->user_id,
			//$companyMember->roles
        );
    }
}
