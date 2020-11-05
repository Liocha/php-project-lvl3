<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Str;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $latestChecks = DB::table('domains')
                            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
                            ->select('domains.id', 'domains.name', DB::raw('MAX(domain_checks.created_at) as last_check'))
                            ->groupBy('domains.id');
                            

        $domains = DB::table('domain_checks')
                    ->select('latestChecks.id', 'latestChecks.name', 'latestChecks.last_check', 'domain_checks.status_code')
                    ->RightJoinSub($latestChecks, 'latestChecks', function ($join) {
                        $join->on('latestChecks.id', '=', 'domain_checks.domain_id')
                                ->on('latestChecks.last_check', '=', 'domain_checks.created_at');
                    })->orderBy('latestChecks.id')
                    ->get();

        return view('pages.domains.index', ['domains' => $domains]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain.name' => 'url'
        ]);

        if ($validator->fails()) {
            flash('Not a valid url')->error();
            return redirect()->back()->withInput();
        }


        $url = $request->input('domain.name');
        ['scheme' => $scheme, 'host' => $host] = parse_url($url);
        $name = "{$scheme}://{$host}";
        $id = DB::table('domains')->where('name', $name)->value('id');

        if (!is_null($id)) {
            flash('Url already exists')->warning();
            return redirect()->route('show', ['id' => $id]);
        }

        $id = DB::table('domains')->insertGetId(
            [
                'name' => $name,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );

        flash('Url has been added')->success();

        return redirect()->route('show', ['id' => $id]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        $checks = DB::table('domain_checks')->where('domain_id', $id)->get();
        return view('pages.domains.show', ['domain' => $domain , 'checks' => $checks]);
    }


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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dump($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }
}
