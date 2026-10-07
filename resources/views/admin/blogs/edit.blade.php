@section('titre', 'Modifier le blog')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="card radius-15">
                <div class="card-body">

                    {{-- ✅ Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-title">
                        <h1>Modifier le Blog</h1>
                    </div>

                    <form action="{{ route('admin.blogs.update', $blog->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text"
                                   name="titre"
                                   class="form-control"
                                   value="{{ old('titre', $blog->titre) }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>

                            <div id="description-editor" class="form-control" style="height:200px;">
                                {!! old('description', $blog->description) !!}
                            </div>

                            <textarea name="description" id="description" hidden></textarea>
                        </div>

                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control">

                            @if($blog->photo)
                                <img src="{{ $blog->photo }}" class="mt-2" width="120">
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <input type="date"
                                   name="date"
                                   class="form-control"
                                   value="{{ old('date', optional($blog->date)->format('Y-m-d')) }}">
                        </div>

                        <button class="btn btn-primary mt-3">
                            Mettre à jour
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        var quill = new Quill('#description-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'image'],
                    ['blockquote']
                ]
            }
        });

        document.querySelector('form').addEventListener('submit', function () {
            document.getElementById('description').value = quill.root.innerHTML;
        });
    </script>
@endsection
