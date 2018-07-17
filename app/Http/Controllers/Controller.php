<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Game;
use App\Http\Helpers\Meme;
use App\Http\Helpers\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Helpers\User;

class Controller extends BaseController
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Initialize this call, get all relavant info
     * @param Request $request
     */
    protected function init(Request $request)
    {
        $userId = $request->session()->get('userId');
        if(!isset($userId)){
            $this->user = new User();
            if(isset($this->user->userId)){
                Cache::put('user_'.$this->user->userId, serialize($this->user), 30);
            }
            $request->session()->put('userId' ,$this->user->userId);
            $request->session()->save();
        }else{
            $this->user = unserialize(Cache::get('user_' . $userId));
            if(empty($this->user)){
                $this->user = new User($userId);
                Cache::put('user_'.$this->user->userId, $this->user->serialize(), 30);
            }elseif(is_array($this->user)){
                $aUser = $this->user;
                $this->user = new User();
                $this->user->initFromArray($aUser);
            }
        }

    }

    /**
     * @param Room $room
     * @return array
     */
    protected function getRoomInfo($room){
        if($room) {
            $roomInfo = $room->getOutput();
            if ($roomInfo && isset($roomInfo['game']) && !empty($roomInfo['game'])) {
                $game = $roomInfo['game'];
                $scoreOrange = 0;
                $scoreBlue = 0;
                $isCaptain = ($this->user->userId == $game->orangeCaptainId || $this->user->userId == $game->blueCaptainId);
                $gameOver = ($game->winningTeam == Game::TURN_ORANGE || $game->winningTeam == Game::TURN_BLUE);

                $memes = $game->memes;
                $processedMemes = [];
                if(is_array($memes)) {
                    foreach ($memes as $meme) {
                        if (!$meme->selected) {
                            if ($meme->status == Meme::STATUS_ORANGE) {
                                $scoreOrange++;
                            } elseif ($meme->status == Meme::STATUS_BLUE) {
                                $scoreBlue++;
                            }
                            if (!$isCaptain && !$gameOver) {
                                $meme->status = 'default';
                            }
                        }

                        $processedMemes[] = $meme;
                    }
                }

                $words = $game->words;
                $processedWords = [];
                if(is_array($words)) {
                    foreach ($words as $word) {
                        if (!$word->selected) {
                            if ($word->status == Meme::STATUS_ORANGE) {
                                $scoreOrange++;
                            } elseif ($word->status == Meme::STATUS_BLUE) {
                                $scoreBlue++;
                            }
                            if (!$isCaptain && !$gameOver) {
                                $word->status = 'default';
                            }
                        }

                        $processedWords[] = $word;
                    }
                }
                
                $game = $game->toArray();
                $game['memes'] = $processedMemes;
                $game['words'] = $processedWords;
                $game['scoreOrange'] = $scoreOrange;
                $game['scoreBlue'] = $scoreBlue;;
                $roomInfo['game'] = $game;
            }
        }else{
            $roomInfo = null;
        }
        return $roomInfo;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return sprintf(
            "%s://%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }

    /**
     * Returns true if we detect a bot in the user agent string
     * @return bool
     */
    protected function isBotRequest() {
        return (
            isset($_SERVER['HTTP_USER_AGENT'])
            && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
        );
    }
}
