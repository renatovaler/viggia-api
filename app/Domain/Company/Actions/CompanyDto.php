<?php declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;

final class CompanyDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly int $ownerUserId
     * @param readonly string $name
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly int $ownerUserId,
        public readonly string $name
    ) {}

    public static function fromModel(Company $company): self
    {
        return new self(
            $company->id,
            $company->owner_user_id,
            $company->name
        );
    }
}
