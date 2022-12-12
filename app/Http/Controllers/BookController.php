<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function createBook(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $title = $request->input('title');
            $author = $request->input('author');

            $newBook = new Book();
            $newBook->title = $title;
            $newBook->author = $author;
            $newBook->user_id = $userId;
            $newBook->save();

            return response()->json([
                "success" => true,
                "message" => "Book created"
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Error creating book: '.$th->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Error creating Book"
            ], 201);
        }
    }
}
