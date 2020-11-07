<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Str;

class CheckController extends Controller
{
    public function checks($id)
    {
        $domain = DB::table('domains')->where('id', $id)->value('name');
        $response = Http::get($domain);
        $status = $response->status();
        $body = $response->body();
        $document = new Document($body);
        $h1 = optional($document->first('h1'))->text();
        $keywords = optional($document->first('meta[name=keywords]'))->content;
        $description = optional($document->first('meta[name=description]'))->content;
        $id = DB::table('domain_checks')->insertGetId(
            [
                'domain_id' => $id,
                'status_code' => $status,
                'h1' => Str::limit($h1, 10, '...'),
                'keywords' => Str::limit($keywords, 30, '...'),
                'description' => Str::limit($description, 30, '...'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );

        return redirect()->back();
    }
}