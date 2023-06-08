<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\WatchList;

class ReportController extends Controller
{
    public function ReportUserRegis()
    {
        date_default_timezone_set('Asia/Jakarta');

        $users = DB::table('users')
            ->select(
                DB::raw("(COUNT(*)) as jumlah_user"),
                DB::raw("MONTHNAME(created_at) as bulan_sekarang")
            )
            ->groupBy('bulan_sekarang')
            ->get();

        return $users;
    }

    public function AvgWatchListByDay()
    {
        date_default_timezone_set('Asia/Jakarta');

        $watch_list_users = DB::table('watch_lists')
            ->join('users', 'watch_lists.user_id', '=', 'users.id')
            ->select(
                DB::raw("users.email as user_email"),
                DB::raw("(COUNT(user_id)) as today"),


            )
            ->groupBy('user_email')
            ->whereDate('added_at', Carbon::today())
            ->get();

        return $watch_list_users;
    }

    public function TotalAddWatchList()
    {
        $watch_list_users = DB::table('watch_lists')
            ->join('users', 'watch_lists.user_id', '=', 'users.id')
            ->select(
                DB::raw('users.id as id_user'),
                DB::raw('users.name as user_name'),
                DB::raw('count(watch_lists.user_id) as total_watch_list')
            )
            ->orderBy('id_user', 'asc')
            ->groupBy('user_name', 'id_user')
            ->get();

        return $watch_list_users;
    }

    public function MonthlyRank()
    {
        $watch_list = WatchList::all();

        $result = [];

        foreach ($watch_list as $item) {
            //get http request to api movie
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MOVIE_API_KEY'),
                'accept' => 'application/json'
            ])->get('https://api.themoviedb.org/3/movie/' . $item->movie_id . '?language=en-US');

            //get response from http
            $data_response = $response->getBody()->getContents();

            //decode response
            $arr = json_decode($data_response, TRUE);

            array_push($result, array('movie_id' => $arr['id'], 'vote_average' => $arr['vote_average'], 'original_title' => $arr['original_title']));

            // $result['movie_id'] = $arr['id'];
            // $result['original_title'] =  $arr['original_title'];
        }

        // $json_product =  json_encode($result);

        return $result;
        die;
        foreach ($result as $item) {
            return $item->movie_id;
        }
    }
}
