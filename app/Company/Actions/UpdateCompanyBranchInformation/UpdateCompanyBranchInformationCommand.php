<?php declare(strict_types=1);

namespace App\Company\Actions\UpdateCompanyBranchInformation;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateCompanyBranchInformationCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
}
