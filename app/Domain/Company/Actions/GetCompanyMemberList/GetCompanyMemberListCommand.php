<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyMemberList;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetCompanyMemberListCommand
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
