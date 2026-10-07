<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all(); // Récupérer tous les blogs
        return view('admin.blogs.index', compact('blogs')); // Retourner la vue avec les blogs
    }
    public function list()
    {
        $blogs = Blog::all(); // Récupérer tous les blogs
        return view('front.blogs.index', compact('blogs')); // Retourner la vue avec les blogs
    }

    // Afficher le formulaire de création de blog
    public function create()
    {
        return view('admin.blogs.create'); // Retourner la vue de création
    }

    // Enregistrer un nouveau blog
    public function store(Request $request)
    {
        try {
            // ✅ Validation
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date' => 'nullable|date',
            ]);

            $blog = new Blog();
            $blog->titre = $request->input('titre');
            $blog->description = $request->input('description');
            $blog->date = $request->input('date');

            // Gestion de l'upload de la photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/blog_photos');
                $blog->photo = Storage::url($path);
            }

            $blog->save(); // Sauvegarder le blog dans la base de données

            return redirect()->route('admin.blogs.index')->with('success', 'Blog créé avec succès.');
        } catch (\Exception $e) {

            // 🔎 Log pour debug
            Log::error('Erreur création blog', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du blog.');
        }
    }

    // Afficher le formulaire d'édition d'un blog
    public function edit($id)
    {
        $blog = Blog::findOrFail($id); // Trouver le blog par son ID
        return view('admin.blogs.edit', compact('blog')); // Retourner la vue d'édition avec les données du blog
    }

    // Mettre à jour un blog existant
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date' => 'nullable|date',
            ]);

            $blog = Blog::findOrFail($id);
            $blog->titre = $request->input('titre');
            $blog->description = $request->input('description');
            $blog->date = $request->input('date');

            // Gestion de l'upload de la photo (mise à jour)
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($blog->photo) {
                    Storage::delete(str_replace('/storage', 'public', $blog->photo));
                }

                $path = $request->file('photo')->store('public/blog_photos');
                $blog->photo = Storage::url($path);
            }

            $blog->save(); // Sauvegarder les changements dans la base de données

            return redirect()->route('admin.blogs.index')->with('success', 'Blog mis à jour avec succès.');
        } catch (\Exception $e) {

            // 🔎 Log serveur (important)
            Log::error('Erreur mise à jour blog', [
                'blog_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du blog.');
        }
    }

    // Supprimer un blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // Supprimer la photo du stockage
        if ($blog->photo) {
            Storage::delete(str_replace('/storage', 'public', $blog->photo));
        }

        $blog->delete(); // Supprimer le blog de la base de données

        return redirect()->route('admin.blogs.index')->with('success', 'Blog supprimé avec succès.');
    }
    public function show($id)
    {
        $blog = Blog::findOrFail($id);

        return view('front.blogs.show', compact('blog'));
    }
}
