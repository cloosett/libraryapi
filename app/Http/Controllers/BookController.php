<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\UserResource;
use App\Models\Book;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $unhashedtoken = $request->bearerToken();
        $token = explode('|', $unhashedtoken)[1];

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $personalAccessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();

        if (!$personalAccessToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $personalAccessToken->tokenable;

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return UserResource::make($user)->resolve();
    }


    public function handleBooks(Request $request, OpenAIService $openAIService)
    {
        if ($request->isMethod('get')) {
            $books = Book::paginate(10);
            return BookResource::collection($books);
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required',
                'author' => 'required',
                'year' => 'required'
            ]);

            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'year' => $request->year,
            ]);

            $description = $openAIService->roman($request->title);
            $book->description = $description;
            $book->save();

            return BookResource::make($book);
        }
    }
}
