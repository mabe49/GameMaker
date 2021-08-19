<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::all();
        return Inertia::render('Player/Index', ['players' => $players]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Player::create(
            Request::validate([
                'name' => ['required'],
                'player_strength' => ['required'],
                'attendance' => [],
            ])
            );

            return Redirect::route('player.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Player::create(
        //     Request::validate([
        //         'name' => ['required'],
        //         'player_strength' => ['required'],
        //         'attendance' => ['required'],
        //     ])
        //     );

        // $player->update(
        //     Request::validate([
        //                 'name' => ['required'],
        //                 'player_strength' => ['required'],
        //                 'attendance' => ['required'],
        //             ])
        // );
        // return Redirect::route('player.index');
        Player::find($id)->update(
            Request::validate([
                'name' => ['required'],
                'player_strength' => ['required'],
                'attendance' => ['required'],
            ])
        );
        return Redirect::route('player.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return Redirect::route('player.index');
    }
}
