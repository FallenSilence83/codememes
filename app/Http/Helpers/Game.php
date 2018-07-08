<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * Class Game
 */
class Game implements \Serializable, \JsonSerializable
{
    /**
     * @var string
     */
    protected $gameId;
    /**
     * @var string
     */
    protected $orangeCaptainId;
    /**
     * @var string
     */
    protected $blueCaptainId;
    /**
     * @var string
     */
    protected $memes;
    /**
     * @var $turn
     */
    protected $turn;
    /**
     * @var $clueWord
     */
    protected $clueWord;
    /**
     * @var $clueNumber
     */
    protected $clueNumber;
    /**
     * @var $winningTeam
     */
    protected $winningTeam;

    const TURN_ORANGE = 'orange';
    const TURN_BLUE = 'blue';

    const TEAM_ONE_CARDS = 9;
    const TEAM_TWO_CARDS = 8;
    const NEUTRAL_CARDS = 6;
    const RICK_CARDS = 1;

    /**
     * Game constructor.
     * @param string $gameId
     */
    public function __construct($gameId = null, $orangeCaptainId = null, $blueCaptainId = null)
    {
        $this->gameId = $gameId;
        if(empty($gameId)){
            $this->gameId = uniqid();
        }
        $aGame = Cache::get('game_'.$this->gameId);
        if($aGame){
            $this->unserialize($aGame);
        }else{
            //build the board
            $totaCount = self::TEAM_ONE_CARDS + self::TEAM_TWO_CARDS + self::NEUTRAL_CARDS + self::RICK_CARDS;
            $this->memes = Meme::getMemes($totaCount);
            shuffle($this->memes);
            $this->turn = (rand(0,1) == 1) ? self::TURN_BLUE : self::TURN_ORANGE;
            if($this->turn == self::TURN_BLUE){
                $blueCount = self::TEAM_ONE_CARDS;
                $orangeCount = self::TEAM_TWO_CARDS;
            }else{
                $orangeCount = self::TEAM_ONE_CARDS;
                $blueCount = self::TEAM_TWO_CARDS;
            }
            $index = 0;
            while($blueCount > 0){
                $this->memes[$index]->status = MEME::STATUS_BLUE;
                $index++;
                $blueCount--;
            }
            while($orangeCount > 0){
                $this->memes[$index]->status = MEME::STATUS_ORANGE;
                $index++;
                $orangeCount--;
            }
            $this->memes[$index]->status = MEME::STATUS_RICK;
            shuffle($this->memes);
            $this->orangeCaptainId = $orangeCaptainId;
            $this->blueCaptainId = $blueCaptainId;
        }
    }

    /**
     * @param $memeId
     * @return bool
     */
    public function selectMeme($memeId)
    {
        $success = false;
        for($i=0; $i<count($this->memes); $i++){
            if($this->memes[$i]->memeId == $memeId){
                $this->memes[$i]->selected = true;
                //was this an instant-lose condition?
                if($this->memes[$i]->status == Meme::STATUS_RICK){
                    $this->toggleTurn();
                    $this->winningTeam = $this->turn;
                }else{
                    //evaluate win conditions
                    $scoreOrange = 0;
                    $scoreBlue = 0;
                    foreach($this->memes as $meme){
                        if(!$meme->selected){
                            if($meme->status == Meme::STATUS_ORANGE){
                                $scoreOrange++;
                            }elseif($meme->status == Meme::STATUS_BLUE){
                                $scoreBlue++;
                            }
                        }
                    }
                    if($scoreBlue == 0){
                        $this->winningTeam = self::TURN_BLUE;
                    }elseif($scoreOrange == 0){
                        $this->winningTeam = self::TURN_ORANGE;
                    }

                    if($this->memes[$i]->status != $this->turn || $this->clueNumber <= 0){
                        $this->toggleTurn();
                    }else{
                        $this->clueNumber = intval($this->clueNumber) -1;
                    }
                }

                $success = true;
                break;
            }
        }
        return $success;
    }

    public function toggleTurn()
    {
        $this->turn = ($this->turn == self::TURN_BLUE) ? self::TURN_ORANGE : self::TURN_BLUE;
        $this->clueWord = null;
        $this->clueNumber = null;
    }

    /**
     * save the room to cache
     */
    public function save()
    {
        Cache::put('game_'.$this->gameId, $this->serialize(), 30);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
        //TODO more
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->initFromArray(unserialize($data));
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'gameId' => $this->gameId,
            'memes' => $this->memes,
            'turn' => $this->turn,
            'orangeCaptainId' => $this->orangeCaptainId,
            'blueCaptainId' => $this->blueCaptainId,
            'clueWord' => $this->clueWord,
            'clueNumber' => $this->clueNumber,
            'winningTeam' => $this->winningTeam
        ];
    }

    /**
     * @param $aGame
     */
    public function initFromArray($aGame)
    {
        $this->gameId = (isset($aGame['gameId'])) ? $aGame['gameId'] : null;
        $this->memes = (isset($aGame['memes'])) ? $aGame['memes'] : null;
        $this->turn = (isset($aGame['turn'])) ? $aGame['turn'] : null;
        $this->orangeCaptainId = (isset($aGame['orangeCaptainId'])) ? $aGame['orangeCaptainId'] : null;
        $this->blueCaptainId = (isset($aGame['blueCaptainId'])) ? $aGame['blueCaptainId'] : null;
        $this->clueWord = (isset($aGame['clueWord'])) ? $aGame['clueWord'] : null;
        $this->clueNumber = (isset($aGame['clueNumber'])) ? $aGame['clueNumber'] : null;
        $this->winningTeam = (isset($aGame['winningTeam'])) ? $aGame['winningTeam'] : null;
    }
}