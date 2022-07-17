<?php

namespace Tests\Feature\Company\CompanyMember;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteCompanyMemberTest extends TestCase
{
    use RefreshDatabase;
	use WithFaker;
	
   public function test_guest()
   {
		$this->assertGuest();
   }
}
