@extends('front.fixe')

@section('titre', 'Korean Glam Blog')

@section('body')
    <div class="container mt-5">

        <div class="text-center mb-5">
            <h1>Korean Glam Blog</h1>
            <p class="text-muted">
                Explore our articles on Korean skincare, beauty tips and K-beauty trends
            </p>
        </div>

        @if($blogs->count())
            <div class="row">

                @foreach($blogs as $blog)
                    <div class="col-md-4 mb-4">

                        <article class="card h-100 shadow-sm">

                            <a href="{{ route('blogs.show', $blog->id) }}">
                                <img src="{{ $blog->photo ?? asset('images/blog-placeholder.jpg') }}"
                                     class="card-img-top"
                                     alt="{{ $blog->titre }} - Korean Beauty Blog"
                                     loading="lazy">
                            </a>

                            <div class="card-body d-flex flex-column">

                                @if($blog->date)
                                    <small class="text-muted mb-2">
                                        {{ $blog->date->format('d M Y') }}
                                    </small>
                                @endif

                                <h5 class="card-title">
                                    <a href="{{ route('blogs.show', $blog->id) }}" class="text-dark">
                                        {{ $blog->titre }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($blog->description), 120) }}
                                </p>

                                <a href="{{ route('blogs.show', $blog->id) }}"
                                   class="mt-auto btn btn-outline-primary btn-sm">
                                    Read More
                                </a>

                            </div>
                        </article>

                        {{-- ✅ Schema.org BlogPosting --}}
                        <script type="application/ld+json">
                    {
                      "@context": "https://schema.org",
                      "@type": "BlogPosting",
                      "headline": "{{ $blog->titre }}",
                      "image": "{{ $blog->photo }}",
                      "datePublished": "{{ optional($blog->date)->toDateString() }}",
                      "author": {
                        "@type": "Organization",
                        "name": "Korean Glam"
                      }
                    }
                    </script>

                    </div>
                @endforeach

            </div>
        @else
            <p class="text-center text-muted">No blog posts are available yet.</p>
        @endif

    </div>
@endsection
