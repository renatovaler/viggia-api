<?php declare(strict_types=1);

namespace App\User\Actions\UpdateUserProfilePhoto;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateUserProfilePhoto
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly UploadedFile $profilePhoto
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly UploadedFile $profilePhoto
    ) {}
}
