<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\CommentStoreRequest;



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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
