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

    public function show($id)
    {
        $book = Book::find($id);
        if(!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return BookResource::make($book)->resolve();
    }
}
