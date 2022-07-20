<?php declare(strict_types=1);

namespace App\License\Actions\DeleteLicense;

use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteLicense
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $licenseId
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $licenseId) {}
}
