<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Str;

function getFixturePath($name)
{
    return implode("/", [__DIR__, "..", "fixtures", $name ]);
}

function getTags($content)
{
    $document = new Document($content);
    $h1 = optional($document->first('h1'))->text();
    $keywords = optional($document->first('meta[name=keywords]'))->content;
    $description = optional($document->first('meta[name=description]'))->content;

    return [
    'h1' => Str::limit($h1, 10, '...'),
    'keywords' => Str::limit($keywords, 30, '...'),
    'description' => Str::limit($description, 30, '...')
    ];
}

class DomainCheckControllerTest extends TestCase
{
    private $domainId;

    protected function setUp(): void
    {
        parent::setUp();

        $domain = 'https://test.ru';

        $this->domainId = DB::table('domains')->insertGetId(
            [
                'name' => $domain,
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

        Http::fake(function ($request) use ($content, $status, $headers) {
            return Http::response($content, $status, $headers);
        });

        $response = $this->post(route('checks', ['id' => $this->domainId]));

        $expectedValues = getTags($content);

        $this->assertDatabaseHas('domain_checks', $expectedValues);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }
}
