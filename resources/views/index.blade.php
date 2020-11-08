@extends('layout')

@section('content')
@include('errors')
<main class="flex-grow-1"> 
    <div class="jumbotron jumbotron-fluid">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto">
                    <h1 class="display-3">Page Analyzer</h1>
                    <p class="lead">Check web pages for free</p>
                    <form action="/domains" method="post" class="d-flex justify-content-center" >
                        @csrf
                        <input type="text" name="domain[name]" value="{{ old('domain.name') }}" class="form-control form-control-lg"  placeholder="https://www.example.com">
                        <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 