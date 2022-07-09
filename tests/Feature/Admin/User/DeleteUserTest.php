<?php

namespace Tests\Feature\Admin\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;
	
    public function test_delete_user_with_not_authenticated_user()
    {
        // Cria um usuário comum (não admin)
        $targetUser = $this->createCommonUser();
        
		// Faz a requisição para deletar o registro
		$response = $this->deleteJson('/admin/users/'.$targetUser->id);
		
		// Verifica se o usuário não está logado
        $response->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_delete_user_with_common_user()
    {
        // Cria um usuário comum (não admin)
        $targetUser = $this->createCommonUser();
		
        // Cria um usuário comum (não admin)
        $loggedUser = $this->createCommonUser();
        
		// Faz a requisição para deletar o registro
		$response = $this->actingAs($loggedUser)->deleteJson('/admin/users/'.$targetUser->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_delete_user_with_admin_user()
    {
        // Cria um usuário comum (não admin)
        $targetUser = $this->createCommonUser();
		
        // Cria um usuário admin e super_admin
        $loggedUser = $this->createCommonUser();
        
		// Faz a requisição para deletar o registro
		$response = $this->actingAs($loggedUser)->deleteJson('/admin/users/'.$targetUser->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
        
		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }
}
