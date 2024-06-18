<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\CommentStoreRequest;
use Illuminate\Support\Facades\Gate;



class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
  
    public function store(CommentStoreRequest $request, Post $post)
    {

       // Valider les données du formulaire
        $validatedData = $request->validated();

        // Créer un nouveau commentaire avec les données validées
        $comment = new Comment($validatedData);

        // Associer le commentaire à l'utilisateur actuellement connecté
        $comment->user_id = auth()->user()->id;

        // Associer le commentaire au post spécifié dans l'URL
        $comment->post_id = $post->id;

        // Sauvegarder le commentaire dans la base de données
        $comment->save();

        // Redirection avec un message de succès
        return redirect()->route('post.show', ['post' => $post])->with('success', 'Commentaire ajouté avec succès !');

    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(CommentStoreRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        // Valider les données de la requête
        $validatedData = $request->validated();

        // Mettre à jour le commentaire avec les données validées
        $comment->update($validatedData);

        // Récupérer le post associé au commentaire
        $post = $comment->post; // Accès direct à la relation "post"

        // Redirection avec un message de succès
        return redirect()->route('post.show', ['post' => $post])
                        ->with('success', 'Commentaire mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Vérifier l'autorisation de supprimer le commentaire
        Gate::authorize('delete', $comment);

        // Supprimer le commentaire
        $comment->delete();

        // Redirection avec un message de succès
        return redirect()->route('post.show', ['post' => $comment->post])
                        ->with('success', 'Commentaire supprimé avec succès!');
    }
}
