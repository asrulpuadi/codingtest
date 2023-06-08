<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\GenreMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function AllGenre()
    {
        $result = GenreMovie::all();

        return response()->json($result, 200);
    }

    public function MovieByGenre(Request $request)
    {
        try {
            $id = $request->id;

            $getGenre = GenreMovie::where('id', $id)->first();
            $genre_id = $getGenre->genre_id;

            //get http request to api movie
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                'accept' => 'application/json'
            ])->get('https://api.themoviedb.org/3/movie/' . $genre_id . '/lists?language=en-US&page=1');

            //get response from http
            $data_response = $response->getBody()->getContents();

            //decode response
            $arr = json_decode($data_response, TRUE);

            return response()->json($arr, 200);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function DetailMovie(Request $request)
    {
        try {
            $movie_id = $request->movie_id;

            //get http request to api movie
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                'accept' => 'application/json'
            ])->get('https://api.themoviedb.org/3/movie/' . $movie_id . '?language=en-US');

            //get response from http
            $data_response = $response->getBody()->getContents();

            //decode response
            $arr = json_decode($data_response, TRUE);

            return response()->json($arr, 200);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
