<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HasPermissions;


class CommentController extends Controller
{
    use HasPermissions;
    
    /*
    |-------------------------------------------------------------------------- 
    | Constructor Method
    |-------------------------------------------------------------------------- 
    */
    public function __construct()
    {
        // $this->authorizePermissions('comment');
    }

    /*
    |-------------------------------------------------------------------------- 
    |  Display all comments
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        $comments = Comment::with(['user', 'book'])->get();
        return CommentResource::collection($comments);
    }

    /*
    |-------------------------------------------------------------------------- 
    |  Display a specific comment
    |-------------------------------------------------------------------------- 
    */
    public function show(Comment $comment)
    {
        return new CommentResource($comment->load(['user', 'book']));
    }

    /*
    |-------------------------------------------------------------------------- 
    |  Create a new comment
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        return new CommentResource($comment);
    }

    /*
    |-------------------------------------------------------------------------- 
    |  Update an existing comment
    |-------------------------------------------------------------------------- 
    */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return new CommentResource($comment);
    }

    /*
    |-------------------------------------------------------------------------- 
    |  Delete a comment
    |-------------------------------------------------------------------------- 
    */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 204);
    }
}
