<?php

namespace App\Http\Controllers;


use Log;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



class SearchController extends Controller
{
    public function index()
    {
        return inertia('Posts/Search/Index');
    }

    public function getPosts(Request $request)
    {
        $query = $request->input('query', ''); // La chaîne de recherche
        $perPage = 4; // Nombre d'éléments par page
        $currentPage = $request->input('page', 1); // Page actuelle
        $queryString = "{$query}%"; // Chaîne de recherche avec le caractère de pourcentage pour les recherches "like"

        // Initialisation d'un tableau pour stocker les résultats de recherche pour chaque option
        $resultsByOptions = [
            'Tout' => [],
            'Titres' => [],
            'Auteurs' => [],
            'Catégories' => [],
        ];

        // Crée la requête de base avec les relations nécessaires
        $baseQuery = Post::query()
            ->with(['user', 'image', 'categories'])->latest(); // Inclut les relations 'user', 'image' et 'categories'

        // Requête pour 'Tout' : Recherche dans les titres, le contenu, les auteurs et les catégories
        $postsByAll = $baseQuery->clone()->where(function ($query) use ($queryString) {
            $query->where('title', 'like', $queryString)
                ->orWhere('content', 'like', $queryString)
                ->orWhereHas('user', function ($query) use ($queryString) {
                    $query->where('name', 'like', $queryString);
                })
                ->orWhereHas('categories', function ($query) use ($queryString) {
                    $query->where('name', 'like', $queryString);
                });
        })->paginate($perPage);

        // Requête pour 'Titres' : Recherche uniquement dans les titres
        $postsByTitle = $baseQuery->clone()
            ->where('title', 'like', $queryString)
            ->paginate($perPage);

        // Requête pour 'Auteurs' : Recherche uniquement dans les auteurs
        $postsByAuthor = $baseQuery->clone()
            ->whereHas('user', function ($query) use ($queryString) {
                $query->where('name', 'like', $queryString);
            })
            ->paginate($perPage);

        // Requête pour 'Catégories' : Recherche uniquement dans les catégories
        $postsByCategories = $baseQuery->clone()
            ->whereHas('categories', function ($query) use ($queryString) {
                $query->where('name', 'like', $queryString);
            })
            ->paginate($perPage);

        // Récupération des résultats et pagination pour chaque option
        $resultsByOptions['Tout'] = [
            'posts' => $postsByAll->items(), // Récupère les posts pour 'Tout'
            // 'pagination' => [
            //     'current_page' => $postsByAll->currentPage(),
            //     'last_page' => $postsByAll->lastPage(),
            //     'total' => $postsByAll->total(),
            // ],
        ];
        $resultsByOptions['Titres'] = [
            'posts' => $postsByTitle->items(), // Récupère les posts pour 'Titres'
            'pagination' => [
                'current_page' => $postsByTitle->currentPage(),
                'last_page' => $postsByTitle->lastPage(),
                'total' => $postsByTitle->total(),
                'postSeen'=> ($postsByTitle->currentPage() - 1) * $postsByTitle->perPage() + count($postsByTitle->items())
            ]
        ];
        $resultsByOptions['Auteurs'] = [
            'posts' => $postsByAuthor->items(), // Récupère les posts pour 'Auteurs'
            'pagination' => [
                'current_page' => $postsByAuthor->currentPage(),
                'last_page' => $postsByAuthor->lastPage(),
                'total' => $postsByAuthor->total(),
                'postSeen'=> ($postsByAuthor->currentPage() - 1) * $postsByAuthor->perPage() + count($postsByAuthor->items())
            ]
        ];
        $resultsByOptions['Catégories'] = [
            'posts' => $postsByCategories->items(), // Récupère les posts pour 'Catégories'
            'pagination' => [
                'current_page' => $postsByCategories->currentPage(),
                'last_page' => $postsByCategories->lastPage(),
                'total' => $postsByCategories->total(),
                'postSeen'=> ($postsByCategories->currentPage() - 1) * $postsByCategories->perPage() + count($postsByCategories->items())
            ],
        ];
        

        // Retourne les résultats en format JSON
        return response()->json([
            'results' => $resultsByOptions, // Envoie des résultats organisés par option
        ]);
    }

    public function getPostsByPage(Request $request)
    {
        // Récupération de la chaîne de recherche à partir de la requête, par défaut une chaîne vide
        $query = $request->input('query', '');
    
        // Nombre d'articles à afficher par page
        $perPage = 4;
    
        // Page actuelle demandée, avec une valeur par défaut de 2
        $currentPage = $request->input('page', 2);
    
        // Préparation de la chaîne de recherche pour les requêtes "LIKE" en SQL
        $queryString = "{$query}%";
    
        // Catégorie de filtrage spécifiée dans la requête, par défaut "Tout"
        $category = $request->input('category', 'Tout');

    
        // Création de la requête de base pour récupérer les articles avec les relations nécessaires
        $baseQuery = Post::query()
            ->with(['user', 'image', 'categories']) // Inclut les relations 'user', 'image' et 'categories'
            ->latest(); // Trie les articles par date de création (du plus récent au plus ancien)
    
        // Initialisation du tableau des résultats avec des valeurs par défaut
        $results = [
            'posts' => [],
            'pagination' => [
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ],
        ];
    
        // Filtrage des articles en fonction de la catégorie spécifiée
        switch ($category) {
            case 'Titres':
                // Filtrage par titre des articles
                $posts = $baseQuery->clone()
                    ->where('title', 'like', $queryString) // Recherche dans les titres des articles
                    ->paginate($perPage); // Pagination des résultats
                break;
    
            case 'Auteurs':
                // Filtrage par nom d'auteur
                $posts = $baseQuery->clone()
                    ->whereHas('user', function ($query) use ($queryString) {
                        $query->where('name', 'like', $queryString); // Recherche dans les noms des auteurs
                    })
                    ->paginate($perPage, ['*'], 'page', $currentPage); // Pagination des résultats
                break;
    
            case 'Catégories':
                // Filtrage par nom de catégorie
                $posts = $baseQuery->clone()
                    ->whereHas('categories', function ($query) use ($queryString) {
                        $query->where('name', 'like', $queryString); // Recherche dans les noms des catégories
                    })
                    ->paginate($perPage, ['*'], 'page', $currentPage); // Pagination des résultats
                break;
    
            case 'Tout':
            default:
                // Filtrage global si aucune catégorie spécifique n'est sélectionnée
                $posts = $baseQuery->clone()->where(function ($query) use ($queryString) {
                    $query->where('title', 'like', $queryString) // Recherche dans les titres
                        ->orWhere('content', 'like', $queryString) // Recherche dans le contenu des articles
                        ->orWhereHas('user', function ($query) use ($queryString) {
                            $query->where('name', 'like', $queryString); // Recherche dans les noms des auteurs
                        })
                        ->orWhereHas('categories', function ($query) use ($queryString) {
                            $query->where('name', 'like', $queryString); // Recherche dans les noms des catégories
                        });
                })->paginate($perPage, ['*'], 'page', $currentPage); // Pagination des résultats
                break;
        }
   
        return response()->json([
            'results' => [
                'data' => $posts->items(), // Les posts de la page actuelle
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ],
        ]);
    }
   

    
    public function testData(Request $request)
    {
        $posts = Post::paginate(5); // Paginée avec 10 posts par page
    
        // Retourne les résultats en format JSON
        return response()->json([
            'results' => [
                'data' => $posts->items(), // Les posts de la page actuelle
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ],
        ]);
    }
    public function testCode()
    {
        return inertia('Posts/TestCode/Index');
    }
   
}
