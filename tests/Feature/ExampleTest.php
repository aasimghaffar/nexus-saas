<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_installer_redirect_is_active(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
