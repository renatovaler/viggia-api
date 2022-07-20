<?php declare(strict_types=1);

namespace App\Company\Actions\GetCompany;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetCompany
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly null|int $id
     *
     * @return void (implicit)
     */
    public function __construct(public readonly null|int $id) {}
}
