<?php declare(strict_types=1);

namespace App\Company\Actions\AddCompanyMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class AddCompanyMemberCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyId
     * @param readonly int $userId
     *
     * @return void (implicit)
     */
    public function __construct(
      public readonly int $companyId,
      public readonly int $userId
	  ) {}
}
