<?php declare(strict_types=1);

namespace App\Domain\License\Actions;

use Illuminate\Support\Carbon;
use App\Domain\License\Models\License;

final class LicenseInformationDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
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
        public readonly int $id,
        public readonly string $slaveKey,
        public readonly int|null $userId,
        public readonly int|null $companyId,
        public readonly int|null $companyBranchId,
        public readonly string|null $runtimeKeyUsedToActivate,
        public readonly string|null $tokenUsedToActivate,
        public readonly Carbon|null $activatedAt,
        public readonly Carbon|null $deactivatedAt
    ) {}

    public static function fromModel(License $license): self
    {
        return new self(
            $license->id,
            $license->slaveKey,
            $license->userId,
            $license->companyId,
            $license->companyBranchId,
            $license->runtimeKeyUsedToActivate,
            $license->tokenUsedToActivate,
            $license->activatedAt,
            $license->deactivatedAt
        );
    }
}
