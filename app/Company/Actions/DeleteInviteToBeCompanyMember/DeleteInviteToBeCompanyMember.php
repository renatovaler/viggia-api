<?php declare(strict_types=1);

namespace App\Company\Actions\DeleteInviteToBeCompanyMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteInviteToBeCompanyMember
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $inviteId
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $inviteId) {}
}
