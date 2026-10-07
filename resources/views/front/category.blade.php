@extends('front.fixe')
@section('titre', 'Categories')
@section('body')
<br><br>
<div class="container">
    <div class="container">
        <h3><b>Categories</b></h3>
        <hr>
        <div class="row">
            @foreach ($categories as $item)
                <div class="col-sm-3">
                    <div class="card text-center p-2 m-1">
                        <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nom }}" height="100" >
                        <br>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('shop', ['idSelectedCategory' => $item->id]) }}">
                                    {{ $item->nom }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
