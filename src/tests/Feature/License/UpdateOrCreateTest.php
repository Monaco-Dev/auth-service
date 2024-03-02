<?php

namespace Tests\Feature\License;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Models\License;
use App\Models\User;

class UpdateOrCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'licenses.updateOrCreate';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
            ->assertUnauthorized();
    }

    /**
     * Test unverified email response
     */
    public function test_unverified_email(): void
    {
        $auth = $this->login(
            User::factory()
                ->unverified()
                ->create()
                ->email
        );

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route))
            ->assertForbidden()
            ->assertSeeText('Your email address is not verified');
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasLicense()->create();
        $license = License::factory()->make();

        Storage::fake('photos');

        $payload = [
            'license_number' => $license->license_number,
            'expiration_date' => $license->expiration_date,
            'file' => UploadedFile::fake()->image('photo1.jpg')
        ];

        $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data'
            ])
            ->post(route($this->route), $payload)
            ->assertOk();
    }
}
