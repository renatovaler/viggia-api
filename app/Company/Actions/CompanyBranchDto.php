<?php declare(strict_types=1);

namespace App\Company\Actions;

use App\Company\Models\CompanyBranch;

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
        public readonly int $companyId,
        public readonly string $name
    ) {}

    public static function fromModel(CompanyBranch $companyBranch): self
    {
        return new self(
            $companyBranch->id,
            $companyBranch->company_id,
            $companyBranch->name
        );
    }
}
