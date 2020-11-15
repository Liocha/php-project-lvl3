<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = DB::table('domains')
                    ->select('name', 'id')
                    ->get();

        $latestChecks = DB::table('domain_checks')
                            ->select('domain_id', 'created_at', 'status_code')
                            ->whereIn('id', function ($query) {
                                $query->select(DB::raw('MAX(id)'))
                                    ->from('domain_checks')
                                    ->groupBy('domain_id');
                            })
                            ->orderBy('domain_id')
                            ->get();
        
        return view('domains.index', ['domains' => $domains], ['latestChecks' => $latestChecks->keyBy('domain_id')]);
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

        $domain = DB::table('domains')->where('name', $name)->first();
        
        if (!is_null($domain)) {
            $id = $domain->id;
            flash('Url already exists')->warning();
        } else {
            $id = DB::table('domains')->insertGetId(
                [
                    'name' => $name,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
            flash('Url has been added')->success();
        }

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
        $domain = DB::table('domains')->find($id);
        abort_if(is_null($domain), 404);

        $checks = DB::table('domain_checks')->where('domain_id', $id)->get();

        return view('domains.show', compact('domain', 'checks'));
    }
}
