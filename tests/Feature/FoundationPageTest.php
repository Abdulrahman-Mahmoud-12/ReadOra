<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationPageTest extends TestCase
{
    public function test_home_page_renders_phase_one_foundation_shell(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('ReadOra')
            ->assertSee('Browse Library')
            ->assertSee('AI-Powered Library Management')
            ->assertSee('data-theme-menu', false)
            ->assertSee('data-mobile-menu-toggle', false);
    }
}
