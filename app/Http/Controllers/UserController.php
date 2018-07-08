<?php

namespace App\Http\Controllers;
use App\Http\Helpers\Game;
use App\Http\Helpers\Meme;
use App\Http\Helpers\Room;
use App\Http\Helpers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function updateUser(Request $request)
    {
        $this->init($request);

        if(isset($this->user) && $this->user->userId != null){
            if(!empty($request->input('roomId'))){
                $this->user->gameId = $request->input('roomId');
            }
            if(!empty($request->input('gameId'))){
                $this->user->gameId = $request->input('gameId');
            }
            if(!empty($request->input('displayName'))){
                $this->user->displayName = $request->input('displayName');
            }
            if(!empty($request->input('team'))){
                $team = $request->input('team');
                if(in_array($team, [User::TEAM_ORANGE, User::TEAM_BLUE]))
                    $this->user->team = $request->input('team');
                else
                    $this->user->team = null;
            }
            if(!empty($request->input('isCaptain'))){
                $this->user->isCaptain = ($request->input('isCaptain') == 'true');
            }
            Cache::put('user_'.$this->user->userId, $this->user->serialize(), 30);
        }
        return response()->json($this->user->toArray());
    }

    public function status(Request $request)
    {
        try {
            $this->init($request);
            $room = null;
            $game = null;
            if ($this->user->roomId != null) {
                $room = new Room($this->user->roomId);
            }
            return response()->json([
                'user' => $this->user,
                'room' => $this->getRoomInfo($room)
            ]);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function extend(Request $request){
        try {
            $this->init($request);
            $this->user->save();
            return response()->json([
                'user' => $this->user,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->user = new User();
        if(isset($this->user->userId)){
            Cache::put('user_'.$this->user->userId, serialize($this->user), 30);
        }
        $request->session()->put('userId' ,$this->user->userId);
        $request->session()->save();
        $this->user->save();
    }
}
