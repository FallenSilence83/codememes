<?php

namespace App\Http\Controllers;
use App\Http\Helpers\Game;
use App\Http\Helpers\Meme;
use App\Http\Helpers\Room;
use App\Http\Helpers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class RoomController extends Controller
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

    /**
     * Display the room page
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function room(Request $request)
    {
        $this->init($request);
        $roomIdParam = $request->input('roomId');
        if(!empty($roomIdParam)){
            //join the specified room
            $joinResponse = $this->join($request);
        }else{
            if($this->user->roomId == null){
                //create new room
                $createResponse = $this->create($request);
            }else{
                try{
                    $room = new Room($this->user->roomId);
                }catch (\Exception $e){
                    //retrieving the user's room failed.  Just make a new room
                    $createResponse = $this->create($request);
                }

            }
        }

        //we should have a room by this point
        try {
            $room = new Room($this->user->roomId);

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
        }catch (\Exception $e){
            $tplVars = [
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ];
        }
        return view('room', $tplVars);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function extend(Request $request){
        try {
            $this->init($request);
            $this->user->save();
            if($this->user->roomId){
                $room = new Room($this->user->roomId);
                $room->save();
                if($room->game){
                    $room->game->save();
                }
            }
            $response = [
                'user' => $this->user,
            ];
            if(isset($room)){
                $response['roomInfo'] = $this->getRoomInfo($room);
            }
            return response()->json($response);
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
    public function status(Request $request)
    {
        $this->init($request);
        //we should have a room by this point
        try {
            $room = new Room($this->user->roomId);

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create a new room
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->init($request);
        try {
            $room = new Room(null, $this->user->userId);
            $room->save();

            $this->user->roomId = $room->roomId;
            $this->user->save();

            return response()->json($room->toArray());
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Join a room
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function join(Request $request)
    {
        $this->init($request);
        try {
            $roomId = $request->input('roomId');
            $room = new Room($roomId);
            $room->addUser($this->user->userId);
            $room->save();

            $this->user->roomId = $room->roomId;
            $this->user->save();
            return response()->json($room->toArray());
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Join a team
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinTeam(Request $request)
    {
        $this->init($request);
        try {
            if($this->user->roomId == null){
                throw new \Exception('User not currently in room');
            }
            $room = new Room($this->user->roomId);

            $team = $request->input('team');
            if($team == User::TEAM_ORANGE){
                $room->addUserToOrange($this->user->userId);
                $this->user->team = User::TEAM_ORANGE;
            }elseif($team == User::TEAM_BLUE){
                $room->addUserToBlue($this->user->userId);
                $this->user->team = User::TEAM_BLUE;
            }else{
                throw new \Exception('Invalid team selection');
            }
            $room->save();
            $this->user->save();

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newGame(Request $request)
    {
        $this->init($request);
        try {
            if($this->user->roomId == null){
                throw new \Exception('User not currently in room');
            }
            $room = new Room($this->user->roomId);

            $orangeCaptainId = $request->input('orangeCaptainId');
            $blueCaptainId = $request->input('blueCaptainId');

            $game = $room->newGame($orangeCaptainId, $blueCaptainId);

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clue(Request $request){

        $this->init($request);
        try {
            $clueWord = $request->input('clue_word');
            if(empty($clueWord)){
                throw new \Exception('Clue Word Required');
            }
            $clueNumber = $request->input('clue_number');
            if(empty($clueNumber)){
                throw new \Exception('Clue Number Required');
            }

            if($this->user->roomId == null){
                throw new \Exception('User not currently in room');
            }
            $room = new Room($this->user->roomId);
            if($room->gameId == null){
                throw new \Exception('No active game in room');
            }
            $game = new Game($room->gameId);
            $game->clueWord = $clueWord;
            $game->clueNumber = $clueNumber;
            $game->save();

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guess(Request $request){

        $this->init($request);
        try {
            $memeId = $request->input('memeId');
            if(empty($memeId)){
                throw new \Exception('Meme Selection Required');
            }

            if($this->user->roomId == null){
                throw new \Exception('User not currently in room');
            }
            $room = new Room($this->user->roomId);
            if($room->gameId == null){
                throw new \Exception('No active game in room');
            }
            $game = new Game($room->gameId);
            $bSaved = $game->selectMeme($memeId);
            if(!$bSaved){
                throw new \Exception('Failed to select meme ' . $memeId);
            }else{
                $game->save();
            }
            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pass(Request $request){

        $this->init($request);
        try {
            if($this->user->roomId == null){
                throw new \Exception('User not currently in room');
            }
            $room = new Room($this->user->roomId);
            if($room->gameId == null){
                throw new \Exception('No active game in room');
            }
            $game = new Game($room->gameId);
            if($game->turn == Game::TURN_BLUE){
                $game->turn = Game::TURN_ORANGE;
            }else{
                $game->turn = Game::TURN_BLUE;
            }
            $game->clueWord = null;
            $game->clueNumber = null;
            $game->save();

            $tplVars = [
                'user' => $this->user,
                'roomInfo' => $this->getRoomInfo($room)
            ];
            return response()->json($tplVars);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine()
            ]);
        }
    }
}
