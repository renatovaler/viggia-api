<?php declare(strict_types=1);

namespace App\Company\Actions\InviteNewCompanyMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class InviteNewCompanyMember
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyId
     * @param readonly string $email
     * @param readonly array $roles
     *
     * @return void (implicit)
     */
    public function __construct(
      public readonly int $companyId,
      public readonly string $email,
      public readonly array $roles
	  ) {}
}
