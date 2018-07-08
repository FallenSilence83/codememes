<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * Class Room
 */
class Room implements \Serializable, \JsonSerializable
{
    /**
     * @var string
     */
    protected $roomId;
    /**
     * @var string
     */
    protected $gameId;
    /**
     * @var string
     */
    protected $hostId;
    /**
     * @var string[]
     */
    protected $userIds;
    /**
     * @var string[]
     */
    protected $orangeTeamIds;
    /**
     * @var string[]
     */
    protected $blueTeamIds;

    /**
     * Room constructor.
     * @param string $roomId
     * @param string $hostId
     * @throws \Exception
     */
    public function __construct($roomId = null, $hostId = null)
    {
        $this->roomId = $roomId;
        if (empty($roomId)) {
            //new room
            $this->roomId = uniqid();
            $this->hostId = $hostId;
            $this->gameId = null;
            $this->userIds = [];
            $this->orangeTeamIds = [];
            $this->blueTeamIds = [];
            if (!empty($hostId)) {
                $this->addUser($hostId);
            }
        } else {
            $aRoom = Cache::get('room_' . $this->roomId);
            if ($aRoom) {
                $this->unserialize($aRoom);
            } else {
                throw new \Exception('Room not found');
            }
        }
    }

    /**
     * save the room to cache
     */
    public function save()
    {
        Cache::put('room_' . $this->roomId, $this->serialize(), 30);
    }

    /**
     * @param $userId
     */
    public function addUser($userId)
    {
        if (!in_array($userId, $this->userIds)) {
            $this->userIds[] = $userId;
        }
    }

    /**
     * @param $userId
     * @return bool
     */
    public function addUserToOrange($userId)
    {
        $result = false;
        if (!in_array($userId, $this->orangeTeamIds)) {
            $this->orangeTeamIds[] = $userId;
            $result = true;
        }
        if (in_array($userId, $this->blueTeamIds)) {
            $index = array_search($userId, $this->blueTeamIds);
            unset($this->blueTeamIds[$index]);
        }
        return $result;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function addUserToBlue($userId)
    {
        $result = false;
        if (!in_array($userId, $this->blueTeamIds)) {
            $this->blueTeamIds[] = $userId;
            $result = true;
        }
        if (in_array($userId, $this->orangeTeamIds)) {
            $index = array_search($userId, $this->orangeTeamIds);
            unset($this->orangeTeamIds[$index]);
        }
        return $result;
    }

    public function newGame($orangeCaptain, $blueCaptain)
    {
        $game = new Game(null, $orangeCaptain, $blueCaptain);
        $this->gameId = $game->gameId;
        $game->save();
        
        //save captain status to users
        $orangeUser = new User($orangeCaptain);
        $orangeUser->isCaptain = true;
        $orangeUser->save();

        $blueUser = new User($blueCaptain);
        $blueUser->isCaptain = true;
        $blueUser->save();

        $this->save();
        return $game;
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
            'roomId' => $this->roomId,
            'hostId' => $this->hostId,
            'gameId' => $this->gameId,
            'userIds' => $this->userIds,
            'orangeTeamIds' => $this->orangeTeamIds,
            'blueTeamIds' => $this->blueTeamIds
        ];
    }

    public function getOutput()
    {
        //fill the game
        $game = null;
        if ($this->gameId != null) {
            $game = new Game($this->gameId);
        }

        //get all the users
        $users = [];
        foreach ($this->userIds as $userId) {
            $users[$userId] = new User($userId);

            if ($game && ($game->orangeCaptainId != $userId || $game->blueCaptainId != $userId)) {
                $users[$userId]->isCaptain = true;
            }
        }
        //fill the orange team
        $orangeTeam = [];
        foreach ($this->orangeTeamIds as $oId) {
            if (array_key_exists($oId, $users)) {
                $orangeTeam[] = $users[$oId];
            }
        }
        //fill the blue team
        $blueTeam = [];
        foreach ($this->blueTeamIds as $bId) {
            if (array_key_exists($bId, $users)) {
                $blueTeam[] = $users[$bId];
            }
        }

        return [
            'room' => $this,
            'game' => $game,
            'users' => $users,
            'orangeTeam' => $orangeTeam,
            'blueTeam' => $blueTeam
        ];
    }

    /**
     * @param $aRoom
     */
    public function initFromArray($aRoom)
    {
        $this->roomId = (isset($aRoom['roomId'])) ? $aRoom['roomId'] : null;
        $this->hostId = (isset($aRoom['hostId'])) ? $aRoom['hostId'] : null;
        $this->gameId = (isset($aRoom['gameId'])) ? $aRoom['gameId'] : null;
        $this->userIds = (isset($aRoom['userIds'])) ? $aRoom['userIds'] : [];
        $this->orangeTeamIds = (isset($aRoom['orangeTeamIds'])) ? $aRoom['orangeTeamIds'] : [];
        $this->blueTeamIds = (isset($aRoom['blueTeamIds'])) ? $aRoom['blueTeamIds'] : [];
    }
}