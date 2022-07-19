<?php declare(strict_types=1);

namespace App\Company\Mail;

use App\Company\Models\CompanyInvitation as CompanyInvitationModel;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CompanyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The team invitation instance.
     *
     * @var \App\Company\Models\CompanyInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \App\Company\Models\CompanyInvitation $invitation
     * @return void
     */
    public function __construct(CompanyInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown(
            'emails.company.company-invitation',
            [
                'acceptUrl' => URL::signedRoute(
                    'companies.company-invitations.accept', ['invitation' => $this->invitation]
                )
            ]
        )->subject(__('Company Invitation'));
    }
}
