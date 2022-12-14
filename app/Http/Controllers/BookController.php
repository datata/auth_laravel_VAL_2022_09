<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function updateBook(Request $request, $id)
    {
        try {
            $userId = auth()->user()->id;
            $newTitle = $request->input('title');
            $newAuthor = $request->input('author');

            // $book = Book::query()
            //     ->where('user_id', $userId)
            //     ->where('id', '=', $id)
            //     ->first();

            $updateBook = Book::where('user_id', $userId)
                ->where('id', $id)
                ->update([
                    'title' => $newTitle,
                    'author' => $newAuthor
                ]);

            // dd($updateBook);

            if(!$updateBook) {
                return response()->json([
                    "success" => true,
                    "message" => "Book doesnt exists"
                ], 404); 
            }

            // $book->title = $newTitle;
            // $book->author = $newAuthor;
            // $book->save();

            return response()->json([
                "success" => true,
                "message" => "Book updated"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Error updating Book"
            ], 500);
        }
    }

    public function getAllBooks()
    {
        Log::info('Getttin Books');
        try {
            $userId = auth()->user()->id;

            // $books = Book::query()
            //     ->where('user_id', '=', $userId)
            //     ->get()
            //     ->toArray();

            // $books = DB::table('books')
            //     ->join('users', 'users.id', '=', 'books.user_id')
            //     ->select('users.email', 'books.title AS titulo_novela')
            //     ->get();

            // $books = User::find($userId)->books;

            $books = User::select('users.id', 'users.email', 'users.name')
                ->with('books:id,title,author,user_id')
                ->find($userId);

            return response()->json([
                "success" => true,
                "message" => "Get Books successfully",
                "data" => $books
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Error getting Book"
            ], 500);
        }
    }
}
