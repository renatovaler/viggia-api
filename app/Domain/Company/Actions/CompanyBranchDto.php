<?php declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\CompanyBranch;

final class CompanyBranchDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly int $ownerCompanyId
     * @param readonly string $name
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly int $ownerCompanyId,
        public readonly string $name
    ) {}

    public static function fromModel(CompanyBranch $companyBranch): self
    {
        return new self(
            $companyBranch->id,
            $companyBranch->owner_company_id,
            $companyBranch->name
        );
    }
}
