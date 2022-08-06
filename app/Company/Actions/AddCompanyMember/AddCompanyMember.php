<?php declare(strict_types=1);

namespace App\Company\Actions\AddCompanyMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class AddCompanyMember
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly string $inviteToken
     * @param readonly int $userId
     *
     * @return void (implicit)
     */
    public function __construct(
      public readonly string $inviteToken,
      public readonly int $userId
	  ) {}
}
