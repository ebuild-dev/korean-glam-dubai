@section('titre', 'Gestion des blogs')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="card radius-15">
                <div class="card-body ">
                    {{-- ✅ Message succès --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ❌ Message erreur générale --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- ❌ Erreurs de validation --}}
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
                        <div class="d-flex justify-content-between">
                            <h1>Créer un Blog</h1>
                        </div>
                    </div>

                    <form id="form" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" id="titre" name="titre" class="form-control" value="{{ old('titre') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>

                            <div id="description-editor" class="form-control" style="height: 200px;">
                                {!! old('description') !!}
                            </div>

                            <!-- Champ caché envoyé au serveur -->
                            <textarea name="description" id="description" hidden></textarea>
                        </div>




                        <div class="form-group">
                            <label for="photo">Photo (optionnel)</label>
                            <input type="file" id="photo" name="photo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Créer le Blog</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Ajouter le CSS de Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Ajouter le JS de Quill -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Initialiser Quill pour l'éditeur -->
    <script>
        var quill = new Quill('#description-editor', {
            theme: 'snow', // Vous pouvez aussi utiliser 'bubble' comme thème
            modules: {
                toolbar: [
                    [{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    ['image'],
                    ['blockquote']
                ]
            }
        });

        // Gérer l'envoi du formulaire en récupérant le contenu de Quill
        document.querySelector('form').addEventListener('submit', function() {
            var description = document.querySelector('textarea[name="description"]');
            description.value = quill.root.innerHTML; // Récupérer le contenu de l'éditeur Quill et le mettre dans le champ textarea
        });
    </script>

@endsection
