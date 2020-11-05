@extends('layout')

@section('content')
<main class="flex-grow-1">
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Domains</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Last check</th>
                        <th>Status Code</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($domains as $domain)    
                <tr>
                    <td>{{$domain->id}}</td>
                    <td><a href="{{route('show', ['id' => $domain->id])}}">{{$domain->name}}</a></td>
                    <td>{{$domain->last_check}}</td>
                    <td>{{$domain->status_code}}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
    </div>
  </main>
@endsection