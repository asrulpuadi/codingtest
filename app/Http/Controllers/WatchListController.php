<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Exception;

use App\Models\WatchList;

class WatchListController extends Controller
{
    public function WatchList()
    {
        try {

            $get_watch_list = WatchList::where('user_id', Auth::user()->id)->get();

            $result = [];

            foreach ($get_watch_list as $item) {
                //get http request to api movie
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                    'accept' => 'application/json'
                ])->get('https://api.themoviedb.org/3/movie/' . $item->movie_id . '?language=en-US');

                //get response from http
                $data_response = $response->getBody()->getContents();

                //decode response
                $arr = json_decode($data_response, TRUE);

                array_push($result, $arr);
            }

            return response()->json($result, 200);
        } catch (Exception $exc) {
            return response()->json([
                'message' => $exc->getMessage()
            ], 400);
        }
    }

    public function AddtoWatchList(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $movie_id = $request->movie_id;
            $genre_id = $request->genre_id;
            $note = $request->note;

            date_default_timezone_set('Asia/Jakarta');

            $result = WatchList::create([
                'user_id' => $user_id,
                'movie_id' => $movie_id,
                'genre_id' => $genre_id,
                'note' => $note,
                'added_at' => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                'message' => 'Movie add to watch list successfull',
                'movie' => $result
            ], 200);
        } catch (Exception $exc) {
            return response()->json([
                'message' => $exc->getMessage()
            ], 400);
        }
    }

    public function WatchListById($id)
    {
        try {
            $get_watch_list = WatchList::where('id', $id)->first();

            //get http request to api movie
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                'accept' => 'application/json'
            ])->get('https://api.themoviedb.org/3/movie/' . $get_watch_list->movie_id . '?language=en-US');

            //get response from http
            $data_response = $response->getBody()->getContents();

            return response()->json([
                'movie' => $data_response,
                'note' => $get_watch_list->note
            ], 200);
        } catch (Exception $exc) {
            return response()->json([
                'message' => $exc->getMessage()
            ], 400);
        }
    }

    public function UpdateWatchList(Request $request)
    {
        try {
            $id = $request->id;
            $note = $request->note;

            $result = WatchList::where('id', $id)
                ->update([
                    'note' => $note
                ]);

            return response()->json([
                'message' => 'Update successfully',
                'watchlist' => $result
            ], 200);
        } catch (Exception $exc) {
            return response()->json([
                'message' => $exc->getMessage()
            ], 400);
        }
    }

    public function DeleteWatchList($id)
    {
        WatchList::find($id)->delete();

        return response()->json([
            'message' => 'Delete Successfull',
        ], 200);
    }
}
