<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $games = Game::all();
        // $datas = array_merge([], $games->toArray());

        // foreach ($games as $key => $game) {
        //     $datas[$key]['players'] = array_merge([], Game::find($key + 1)->players()->get()->toArray());
        // }

        $datas = Game::with('players')->get();

        return Inertia::render('Games/Index', ['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $players = Player::where('attendance', 1)->get();
        return Inertia::render('Games/Create', ['players' => $players]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $game = new Game();
        $game->map = $request->map;
        $game->result = $request->result;
        $game->save();

        foreach ($request->aPlayers as $aPlayer) {
            $game->players()->attach($aPlayer);
        }

        foreach ($request->bPlayers as $bPlayer) {
            $game->players()->attach($bPlayer);
        }

        foreach ($game->players as $key => $player) {
            $player->game_count += 1;
            if ($key < 6) {
                if ($game->result === 0) {
                    $player->win_count += 1;
                } elseif ($game->result === 1) {
                    $player->lose_count += 1;
                }
            } else {
                if ($game->result === 0) {
                    $player->lose_count += 1;
                } elseif ($game->result === 1) {
                    $player->win_count += 1;
                }
            }
            $player->save();
        }

        foreach($request->sPlayers as $sPlayer) {
            $spectatePlayer = Player::find($sPlayer);
            $spectatePlayer->rest_count += 1;
            $spectatePlayer->save();
        }

        return Redirect::route('game.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
