<?php

namespace App\Http\Controllers;

use App\Events\ServerUpdated;
use App\Jobs\SignalServer;
use App\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerSignalController extends Controller
{
    /**
     * Start the server.
     *
     * @param Request $request
     * @param Server $server
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, Server $server)
    {
        $request->validate([
            'signal' => ['required', 'in:start,stop,restart'],
        ]);

        $server->status = "{$request->signal} pending";
        $server->save();

        broadcast(new ServerUpdated($server))->toOthers();
        dispatch(new SignalServer($server, $request->signal));

        return response()->json($server->fresh());
    }
}
