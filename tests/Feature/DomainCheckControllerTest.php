<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

function getFixturePath($name)
{
    return implode("/", [__DIR__, "..", "fixtures", $name ]);
}

class DomainCheckControllerTest extends TestCase
{
    private $domainName;
    private $domainId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->domainName = 'https://test.ru';

        $this->domainId = DB::table('domains')->insertGetId(
            [
                'name' => $this->domainName,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }

    public function testCheck()
    {
        $pathToFile = getFixturePath('index.html');
        $content = file_get_contents($pathToFile);
        $status = 200;
        $headers = ['Content-Type' => 'text/html'];
        $expectedValues  = [
            "h1" => "Это тестовая страница для HTTP FAKER",
            "keywords" => "новости, новости сегодня, новости сейчас, медийный портал",
            "description" => "В наше время люди узнают о том, что они думают, по телевизору.",
        ];

        Http::fake(function ($request) use ($content, $status, $headers) {
            return Http::response($content, $status, $headers);
        });

        $response = $this->post(route('checks', ['id' => $this->domainId]));

        $this->assertDatabaseHas('domain_checks', $expectedValues);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }
}
