<?php

namespace Tests\Feature;

use Tests\TestCase;

class TemplateViewTest extends TestCase
{
    public function test_the_template_route_returns_a_successful_response(): void
    {
        $response = $this->get('/t');

        $response->assertStatus(200);
    }
}
