@extends('layout')

@section('content')
@include('errors')
<main class="flex-grow-1" style="color: azure"> 
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg" style="color: azure">
            <div class="row" style="color: azure">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white" style="color: azure">
                    <h1 class="display-3">Page Analyzer</h1>
                    <p class="lead">Check web pages for free</p>
                    <form action="/domains" method="post" class="d-flex justify-content-center" style="color: azure">
                        {{csrf_field()}}
                        <input type="text" name="domain[name]" value="" class="form-control form-control-lg" style="color: azure" d placeholder="https://www.example.com">
                        <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 