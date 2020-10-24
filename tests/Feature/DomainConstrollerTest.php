<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

function addDomain($name)
{
    $id = DB::table('domains')->insertGetId(
        [
            'name' => $name,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]
    );
    return $id;
}


class DomainConstrollerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $domains = ['https://test1.ru', 'http://test2.com', 'https://test3.pro'];

        foreach ($domains as $domain) {
            addDomain($domain);
        }
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
        $response = $this->get(route('show', ['id' => '1']));
        $response->assertOk();
    }

    public function testStore()
    {
        $response = $this->post(route('store'), ['domain' => ['name' => 'https://testtest.ru']]);
        $response->assertRedirect();
        $this->assertDatabaseHas('domains', [
            'name' => 'https://testtest.ru',
        ]);
    }
}
