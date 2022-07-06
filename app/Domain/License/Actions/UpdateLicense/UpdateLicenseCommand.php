<?php declare(strict_types=1);

namespace App\Domain\License\Actions\UpdateLicense;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateLicenseCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $licenseId
     * @param readonly string $slaveKey
     * @param readonly int|null $userId
     * @param readonly int|null $companyId
     * @param readonly int|null $companyBranchId
     * @param readonly string|null $runtimeKeyUsedToActivate
     * @param readonly string|null $tokenUsedToActivate
     * @param readonly string|null $activatedAt
     * @param readonly string|null $deactivatedAt
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $licenseId,
        public readonly string $slaveKey,
        public readonly int|null $userId,
        public readonly int|null $companyId,
        public readonly int|null $companyBranchId,
        public readonly string|null $runtimeKeyUsedToActivate,
        public readonly string|null $tokenUsedToActivate,
        public readonly string|null $activatedAt,
        public readonly string|null $deactivatedAt
	) {}
}
