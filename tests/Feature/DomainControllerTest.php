<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DomainControllerTest extends TestCase
{
    private $domainName;
    private $domainId;
    private $secondDomainName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->domainName = 'https://test.ru';
        $this->secondDomainName = 'https://hexlet.io';

        $this->domainId = DB::table('domains')->insertGetId(
            [
                'name' => $this->domainName,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }

    public function testHome()
    {
        $response = $this->get(route('home'));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('show', ['id' => $this->domainId]));
        $response->assertOk();
        $response->assertSee($this->domainName);
    }

    public function testStore()
    {
        $response = $this->post(route('store'), ['domain' => ['name' => $this->secondDomainName]]);

        $this->assertDatabaseHas('domains', [
            'name' => $this->secondDomainName,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }

    public function testStoreIfDomainAlreadyExist()
    {
        $response = $this->post(route('store'), ['domain' => ['name' => $this->domainName]]);

        $this->assertDatabaseHas('domains', [
            'name' => $this->domainName,
        ]);

        $this->assertDatabaseCount('domains', 1);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }
}
