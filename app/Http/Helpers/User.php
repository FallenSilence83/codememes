<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * Class User
 */
class User implements \Serializable, \JsonSerializable
{
    /**
     * @var string
     */
    protected $userId;
    /**
     * @var string
     */
    protected $roomId;
    /**
     * @var string
     */
    protected $displayName;
    /**
     * @var string
     */
    protected $team;
    /**
     * @var bool
     */
    protected $isCaptain;

    const TEAM_ORANGE = 'orange';
    const TEAM_BLUE = 'blue';

    /**
     * User constructor.
     * @param string $userId
     * @param string $roomId
     * @param string $displayName
     * @param string $team
     * @param bool $isCaptain
     */
    public function __construct($userId = null, $roomId = null, $displayName = null, $team = null, $isCaptain = false)
    {
        $this->userId = $userId;
        $bFresh = true;
        if(empty($userId)){
            $this->userId = uniqid();
        }else{
            $aUser = Cache::get('user_'.$this->userId);
            if($aUser){
                $this->unserialize($aUser);
                $bFresh = false;
            }
        }

        if($bFresh) {
            $this->roomId = $roomId;
            $this->displayName = (!empty($displayName)) ? $displayName : NameGenerator::randomName();
            $this->team = $team;
            $this->isCaptain = $isCaptain;
        }
    }

    public function save()
    {
        Cache::put('user_'.$this->userId, $this->serialize(), 30);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if(property_exists($this, $name)){
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
        if(property_exists($this, $name)){
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
            'userId' => $this->userId,
            'roomId' => $this->roomId,
            'displayName' => $this->displayName,
            'team' => $this->team,
            'isCaptain' => $this->isCaptain
        ];
    }

    /**
     * @param $aUser
     */
    public function initFromArray($aUser)
    {
        $this->userId = (isset($aUser['userId'])) ? $aUser['userId'] : null;
        $this->roomId = (isset($aUser['roomId'])) ? $aUser['roomId'] : null;
        $this->displayName = (isset($aUser['displayName'])) ? $aUser['displayName'] : null;
        $this->team = (isset($aUser['team'])) ? $aUser['team'] : null;
        $this->isCaptain = (isset($aUser['isCaptain'])) ? $aUser['isCaptain'] : null;
    }
}