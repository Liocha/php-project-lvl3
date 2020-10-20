@extends('layout')

@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Site: {{ $domain->name}}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
        <tbody>
            <tr>
                <td>id</td>
                <td>{{$domain->id}}</td>
            </tr>
            <tr>
                <td>name</td>
                <td>{{$domain->name}}</td>
            </tr>
            <tr>
                <td>created_at</td>
                <td>{{$domain->created_at}}</td>
            </tr>
            <tr>
                <td>updated_at</td>
                <td>{{$domain->updated_at}}</td>
            </tr>
        </tbody></table>
    </div>
    <h2 class="mt-5 mb-3">Checks</h2>
    <form method="post" action="#">
        <input type="hidden" name="_token" value="">            <input type="submit" class="btn btn-primary" value="Run check">
    </form>
                <table class="table table-bordered table-hover text-nowrap">
            <tbody><tr>
                <th>Id</th>
                <th>Status Code</th>
                <th>h1</th>
                <th>Keywords</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
                                <tr>
                    <td>626</td>
                    <td>200</td>
                    <td></td>
                    <td></td>
                    <td>Яндекс - поисковая система и и...</td>
                    <td>2020-10-20 21:06:42</td>
                </tr>
                        </tbody></table>
        </div>
@endsection 