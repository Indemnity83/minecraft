<?php

namespace App\Http\Controllers;

use App\Events\ServerCreated;
use App\Events\ServerUpdated;
use App\Jobs\InstallServer;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Server::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required'],
            'profile' => ['required', 'in:vanilla'],
            'version' => ['required'],
        ]);

        $server = Server::make($attributes);
        $server->slug = Str::slug($server->name);
        $server->status = 'installing';
        $server->save();

        broadcast(new ServerCreated($server))->toOthers();
        dispatch(new InstallServer($server));

        return response()->json($server->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Server $server)
    {
        return response()->json($server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
        //
    }
}
