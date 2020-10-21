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
        $domains = DB::table('domains')->get();
        return view('pages.domains', ['domains' => $domains]);
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
            return redirect()->route('home');
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
        $data = DB::table('domains')->where('id', $id)->first();
        return view('pages.site', ['domain' => $data]);
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
