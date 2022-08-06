<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

use App\User\Models\User;
use App\User\Actions\CreateUser\CreateUser;

use App\Company\Actions\AddCompanyMember\AddCompanyMember;
use App\Company\Http\Requests\AcceptCompanyInviteRequest;

use App\Structure\Http\Controllers\Controller;

class AcceptInviteController extends Controller
{
    /**
     * Accept a company invitation
     *
     * @param  \App\Company\Http\Requests\AcceptCompanyInviteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(AcceptCompanyInviteRequest $request): Response
    {
        // Verifica se o usuário existe e, caso não exista, cria e já faz login
        if((new User())->where('email', $request->input('email'))->doesntExist()) {
            // Cria o usuário
            $user = dispatch_sync(new CreateUser(
                $request->name,
                $request->email,
                $request->password,
                null
            ));

            // Adiciona as permissões de user padrão
            foreach(config('roles.default_user_system_roles') as $key => $value) {
                $user->addRoleToUserByName($value);
            }

            // Salva as alterações
            $user->save();

            // Dispara o evento de criação do usuário
            event( new Registered($user) );
            
            // Loga o usuário novo
            Auth::loginUsingId($user->id);
        }

        // Adiciona o usuário como membro da company
        dispatch_sync(new AddCompanyMember(
            $request->input('token'),
            Auth::user()->id
        ));

        return response()->noContent();
    }
}
