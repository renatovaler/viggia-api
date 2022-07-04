<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\SwitchCompany;

use Illuminate\Foundation\Bus\Dispatchable;

final class SwitchCompanyCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyId
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $companyId) {}
}
