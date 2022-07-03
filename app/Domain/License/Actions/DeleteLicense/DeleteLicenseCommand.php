<?php declare(strict_types=1);

namespace App\Domain\License\Actions\DeleteLicense;

use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteLicenseCommand
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
