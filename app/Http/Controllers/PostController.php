<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

class PostController extends Controller
{
    protected $categories;
    protected $user;

    // Constructor to initialize properties
    public function __construct()
    {
        $this->categories = Category::all();
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = 4;
        $posts = Post::with(['user', 'categories', 'image'])
                     ->latest()
                     ->paginate($perPage);

        return Inertia::render('Posts/Index', [
            'posts' => $posts->items(),
            'pagination_links' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Posts/Create', [
            'categories_list' => $this->categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $validatedData = $request->validated();
        $post = new Post($validatedData);
        $post->user_id = $this->user->id;
        $post->save();

        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }

        $this->handleImageUpload($request, $post);

        return Redirect::route('post.list')->with('message', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $article = [
            'post' => $post,
            'categories' => $post->categories,
            'author' => $post->user,
            'image' => $post->image ? $post->image->path : "/images/default-image.jpg",
        ];

        $authUserId = $this->user->id;
        $comments = $post->comments()->with('user')->get()->sortByDesc('created_at');

        [$authUserComments, $otherComments] = $comments->partition(function ($comment) use ($authUserId) {
            return $comment->user_id == $authUserId;
        });

        $mergedComments = $authUserComments->merge($otherComments);

        return Inertia::render('Posts/Show', [
            'post' => $article,
            'comments' => $mergedComments,
            'userID' => $authUserId,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'categories_post' => $post->categories->pluck('id'),
            'categories_list' => $this->categories,
            'image' => $post->image,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostStoreRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        $post->update($validatedData);

        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }

        $this->handleImageUpload($request, $post);

        return Redirect::route('post.user')->with('message', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return Redirect::route('post.user')->with('message', 'Article deleted successfully!');
    }

    /**
     * Display posts of the authenticated user.
     */
    public function postsUser()
    {
        $posts = Post::where('user_id', $this->user->id)
                     ->latest()
                     ->paginate(4);

        $postsData = $posts->map(function ($post) {
            return [
                'post' => $post,
                'categories' => $post->categories,
                'image' => $post->image ? $post->image->path : "/images/default-image.jpg",
            ];
        });

        return Inertia::render('Posts/Author/Posts', [
            'myPosts' => $postsData,
            'pagination_links' => $posts,
        ]);
    }

    private function handleImageUpload(Request $request, Post $post)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $filePath = '/images/' . $fileName;
        
            // Créez une instance de l'image en utilisant Intervention Image
            $imageInstance = ImageFacade::read($image); // Utilisation correcte de make()
        
            // Enregistrez l'image originale
            Storage::disk('public')->put($filePath, (string) $imageInstance->encode());
        
            // Créez une miniature
            $thumbnailPath = '/images/thumbnails/' . $fileName;
            $thumbnailImageInstance = $imageInstance->resize(300, 250, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put($thumbnailPath, (string) $thumbnailImageInstance->encode());
        
            // Vérifiez si une image précédente existe (update)
            if ($post->image) {
                
                // Supprimez l'ancienne image si elle existe
                if ($post->image->path && Storage::disk('public')->exists($post->image->path)) {
                    Storage::disk('public')->delete($post->image->path);
                }

                // Supprimez l'ancienne miniature si elle existe
                if ($post->image->thumbnail_path && Storage::disk('public')->exists($post->image->thumbnail_path)) {
                    Storage::disk('public')->delete($post->image->thumbnail_path);
                }
        
                // Mettez à jour l'enregistrement de l'image
                $post->image->update([
                    'path' => $filePath,
                    'thumbnail_path' => $thumbnailPath
                ]);
            } else {
                // Créez un nouvel enregistrement d'image (create)
                $imageModel = new ImageModel();
                $imageModel->path = $filePath;
                $imageModel->thumbnail_path = $thumbnailPath;
                $imageModel->post()->associate($post);
                $imageModel->save();
            }
        } else {
            // Gérez le cas où aucune image n'est téléchargée
            if (!$post->image) { // Vérifiez si le post n'a pas déjà d'image
                $imageModel = new ImageModel();
                $imageModel->path = "/images/default-image.jpg"; // Chemin correct
                $imageModel->thumbnail_path = "/images/default-thumbnail.jpg"; // Chemin correct
                $imageModel->post()->associate($post);
                $imageModel->save();
            }
        }
    }


}
