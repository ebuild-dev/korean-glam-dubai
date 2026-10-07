@extends('front.fixe')

@section('titre', $blog->titre)

@section('body')
    <div class="container mt-5">
        <h1>{{ $blog->titre }}</h1>

        @if($blog->date)
            <p class="text-muted">{{ $blog->date->format('d M Y') }}</p>
        @endif

        @if($blog->photo)
            <img src="{{ $blog->photo }}" class="img-fluid mb-4" alt="{{ $blog->titre }}">
        @endif

        <div class="blog-content">
            {!! $blog->description !!}
        </div>

        <a href="{{ route('front.blogs.index') }}" class="btn btn-outline-primary mt-4">Back to Blogs</a>
    </div>
@endsection
