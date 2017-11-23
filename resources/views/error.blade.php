@extends('template/layoutError')

@section('title')
    <title>Fail - MoneyCash</title>
@endsection

@section('content')
    <div class="content-table-view">
        <div class="content-table-view2">
            <div class="container-fluid">
                <div class="header text-center">
                    <h3 class="title">Error</h3>
                    <p class="category">
                        Menssagem: {{ $message }}
                    </p>
                </div>
            </div>
        </div>
    </div>    
@endsection
