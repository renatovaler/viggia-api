<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompany;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetCompanyCommand
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
