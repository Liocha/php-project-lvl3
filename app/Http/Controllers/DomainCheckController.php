<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class DomainCheckController extends Controller
{
    public function checks($id)
    {
        $domainName = DB::table('domains')->where('id', $id)->value('name');

        abort_unless($domainName, 404);

        try {
            $response = Http::get($domainName);
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
                    'h1' => $h1,
                    'keywords' => $keywords,
                    'description' => $description,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
            flash("Website has been checked!")->success();
        } catch (RequestException | ConnectionException $e) {
            flash("Данный домен {$domainName} не может быть проверен")->error();
        }
        return redirect()->back();
    }
}
