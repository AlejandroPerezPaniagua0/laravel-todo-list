<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_display_settings_page_and_creates_default_settings()
    {
        $response = $this->actingAs($this->user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('configuration.index');
        $response->assertViewHas('settings');

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->user->id,
            'theme' => 'light',
            'language' => 'en'
        ]);
    }

    /** @test */
    public function it_can_update_user_settings()
    {
        // Initial settings
        UserSetting::factory()->create([
            'user_id' => $this->user->id,
            'theme' => 'light',
            'language' => 'en'
        ]);

        $updateData = [
            'theme' => 'dark',
            'language' => 'es',
            'email_notifications' => true,
            'timezone' => 'Europe/Madrid',
            'date_format' => 'Y-m-d'
        ];

        $response = $this->actingAs($this->user)->put(route('settings.update'), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->user->id,
            'theme' => 'dark',
            'language' => 'es',
            'email_notifications' => true,
            'timezone' => 'Europe/Madrid',
            'date_format' => 'Y-m-d'
        ]);

        $this->assertEquals('es', app()->getLocale());
    }

    /** @test */
    public function it_can_reset_user_settings()
    {
        // Settings with non-default values
        UserSetting::factory()->create([
            'user_id' => $this->user->id,
            'theme' => 'dark',
            'language' => 'es',
            'email_notifications' => false,
            'timezone' => 'America/New_York',
            'date_format' => 'm/d/Y'
        ]);

        $response = $this->actingAs($this->user)->post(route('settings.reset'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->user->id,
            'theme' => 'light',
            'language' => 'en',
            'email_notifications' => true,
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y'
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_settings()
    {
        $response = $this->get(route('settings.index'));
        $response->assertRedirect(route('login'));

        $response = $this->put(route('settings.update'), []);
        $response->assertRedirect(route('login'));

        $response = $this->post(route('settings.reset'));
        $response->assertRedirect(route('login'));
    }
}
