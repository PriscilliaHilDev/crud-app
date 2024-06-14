<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Image;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostStoreRequest;
use App\Models\Category;

class PostController extends Controller
{

    // Déclarez la propriété
    protected $categories;

    // Constructeur pour initialiser la propriété
    public function __construct()
    {
        // Initialisez la propriété avec les catégories
        $this->categories = Category::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // recupérer les post dans l'ordre decroissant de 4 en 4 et ajout de l'image via la relation one to one
    
        $posts = Post::latest()->paginate(8)->map(function ($post) {
            return [
                'post' => $post,
                'categories'=> $post->categories,
                'author' => $post->user,
                'image' => $post->image ? $post->image->path : "/images/default-image.jpg",
            ];
        });

    
        // récuperer les links de la pagination des articles du plus recent au plus ancien
        $pagination = Post::latest()->paginate(4);
       
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'pagination_links' => $pagination
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // envoie des categories dans le multi select du formulaire de creation de post
        return Inertia::render('Posts/Create', [
            'categories_list' => $this->categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = auth()->user();
    
        // Les données validées seront disponibles dans $request->validated()
        $validatedData = $request->validated();
    
        // Créer un nouvel article avec les données validées
        $post = new Post($validatedData);
    
        // Attribuer l'ID de l'utilisateur connecté au champ user_id du post
        $post->user_id = $user->id;
    
        // Enregistrer le post dans la base de données
        $post->save();
    
        // Vérifier si le post a été correctement créé
        if ($post) {
    
            // Enregistrer les catégories associées au post si elles sont présentes dans la requête
            if ($request->has("categories")) {
                $post->categories()->sync($request->input('categories'));
            }
    
            // Enregistrer l'image de l'article si elle a été envoyée dans le formulaire
            if ($request->hasFile('image')) {
    
                // Récupérer le fichier image depuis la requête
                $image = $request->file('image');
    
                // Générer un nom de fichier unique pour l'image
                $fileName = time() . '_' . $image->getClientOriginalName();
    
                // Stocker l'image dans le dossier "images" du stockage public
                $filePath = $image->storeAs('/images', $fileName, 'public');
    
                // Créer une nouvelle instance d'Image pour l'associer au post
                $image = new Image();
                $image->path = $filePath;
                $image->post()->associate($post);
                $image->save();
    
            } else {
                // Si aucun fichier n'est téléchargé, insérer l'URL de l'image par défaut
                $image = new Image();
                $image->path = "/images/default-image.jpg";
                $image->post()->associate($post);
                $image->save();
            }
        }
    
        // Rediriger avec un message de succès
        return redirect()->route('post.list')
                         ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     * Fournir le post directement à la fonction show pour éviter une autre requête à la base de données dans le but de récupérer l'objet post
     */

   
    public function show(Post $post)
    {
        // Accédez directement aux propriétés de l'objet $post
        $article = [
            'post' => $post,
            'categories' => $post->categories,
            'author' => $post->user,
            'image' => $post->image ? $post->image->path : "/images/default-image.jpg",
        ];
    
        return Inertia::render('Posts/Show', [
            'post' => $article,
        ]);
    }
        

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {        
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'categories_post' => $post->categories->pluck('id'), // envoyer que les id des categories pour les selectionner initialement dans le composant multiselect
            'categories_list' => $this->categories,
            'image' => $post->image
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostStoreRequest $request, Post $post)
    {

        // Les données validées seront disponibles dans $request->validated()
        $validatedData = $request->validated();

        $post->update($validatedData);

        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }
        
        if ($request->hasFile('image')) {

            // j'enregistre l'image dans une variable
            $image = $request->file('image');
            // je crée un nom pour cette image
            $fileName = time() . '_' . $image->getClientOriginalName();
            // je stock l'image dans le dossier storage/images
            $filePath = $image->storeAs('/images', $fileName, 'public');

            // si l'article a une image
            if ($post->image) {              

                // je stock l'ancienne image dans une variable
                $oldImage = $post->image->path;

                // si l'ancienne image n'est pas l'image par defaut (pour eviter de la supprimer dans le storage)
                if($oldImage !== "/images/default-image.jpg") {
                    
                    // je verifie que l'ancienne image existe dans le storage avant de le supprimer
                    if(Storage::disk("public")->exists($oldImage)) {
                       
                        (Storage::disk("public")->delete($oldImage));
                   
                    }
    
                // Mettre à jour le chemin de l'image existante
                $post->image->update(['path' => $filePath]);
                }
            } 
        }
    
        // Redirige avec un message de succès
        return redirect()->route('post.list')
                         ->with('success', 'Post updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.user')
        ->with('success', 'Article supprimé avec succès!');
    }

     /**
     * Fonction pour afficher les posts de l'utilisateur connecté
     */
     public function postsUser()
     {
         // Récupérer l'utilisateur actuellement authentifié
         $user = auth()->user();
 
         // Vérifier si l'utilisateur est authentifié
         if (!$user) {
             return redirect()->route('login')->with('error', 'Veuillez vous connecter pour voir vos articles.');
         }

        $getPostsUser = Post::latest()->paginate(4)->where('user_id', $user->id);
         
        $posts = $getPostsUser->map(function ($post) {
            return [
                'post' => $post,
                'categories'=> $post->categories,
                'image' => $post->image ? $post->image->path : "/images/default-image.jpg",
            ];
        });

        $pagination = Post::latest()->where('user_id', $user->id)->paginate(4);

         // Retourner une vue avec les posts
         return Inertia::render('Posts/Author/Posts', [
            'myPosts' => $posts,
            'pagination_links' => $pagination
        ]);

     }
}
