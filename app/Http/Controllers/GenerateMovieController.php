<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\GenreMovie;

class GenerateMovieController extends Controller
{
    public function GenerateGenreMovie()
    {
        try {
            //get http request to api movie
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                'accept' => 'application/json'
            ])->get('https://api.themoviedb.org/3/genre/movie/list');

            //get response from http
            $data_response = $response->getBody()->getContents();

            //decode response
            $arr = json_decode($data_response, TRUE);

            //do loop and condition
            foreach ($arr['genres'] as $genreItem) {
                $cehckGenre = GenreMovie::where('genre_id', $genreItem['id'])->get();

                //if genre doesnt exist
                if (count($cehckGenre) == 0) {
                    GenreMovie::create([
                        'genre_id' => $genreItem['id'],
                        'genre_name' => $genreItem['name']
                    ]);
                }
            }

            $getGenre = GenreMovie::latest()->get();

            return response()->json([
                'message' => 'Generate Genre Movie Successfully',
                'genremovie' => $getGenre
            ], 200);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
