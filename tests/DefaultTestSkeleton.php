<?php

namespace Tests;

interface DefaultTestSkeletonInterface
{
    /**
     * Test with not authenticated user
     *
     * @return void
     */
    public function test_with_not_authenticated_user() {}
	
    /**
     * Test with authenticated but unauthorized user
     *
     * @return void
     */
    public function test_with_authenticated_but_unauthorized_user() {}
	
    /**
     * Test with authenticated and authorized
     *
     * @return void
     */
    public function test_with_authenticated_and_authorized_user() {}
	
    /**
     * Test with authenticated and authorized but with invalid params
     *
     * @return void
     */
    public function test_with_authenticated_and_authorized_user_but_with_invalid_params() {}
