<?php declare(strict_types=1);

namespace App\Company\Actions\GetCompanyBranch;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetCompanyBranchCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $id) {}
}
