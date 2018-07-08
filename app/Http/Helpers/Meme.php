<?php

namespace App\Http\Helpers;

/**
 * Class Meme
 */
class Meme implements \Serializable, \JsonSerializable
{
    /**
     * @var string
     */
    protected $memeId;
    /**
     * @var string
     */
    protected $displayName;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $thumb;
    /**
     * @var string
     */
    protected $youTubeKey;
    /**
     * @var string
     */
    protected $infoUrl;
    /**
     * @var array
     */
    protected $tags;
    /**
     * @var bool
     */
    protected $nsfw;
    /**
     * @var bool
     */
    protected $type;
    /**
     * @var bool
     */
    protected $selected;

    const STATUS_ORANGE = 'orange';
    const STATUS_BLUE = 'blue';
    const STATUS_NEUTRAL = 'neutral';
    const STATUS_RICK = 'rick';

    const TYPE_IMAGE = 'image';
    const TYPE_GIF = 'gif';
    const TYPE_YOUTUBE = 'youtube';
    const TYPE_AUDIO = 'audio';
    const TYPE_WORD = 'word';

    /**
     * Meme constructor.
     * @param string $memeId
     * @param string $displayName
     * @param string $url
     * @param string $status
     * @param bool $selected
     * @param string $thumb
     * @param string $youTubeKey
     * @param string $infoUrl
     * @param array $tags
     * @param bool $nsfw
     */
    public function __construct($memeId = null, $displayName = null, $url = null, $status = null, $selected = false,
                                $thumb = null, $youTubeKey = null, $infoUrl = null, $tags = [], $nsfw = false)
    {
        $this->memeId = $memeId;
        $this->displayName = $displayName;
        $this->url = $url;
        $this->type = self::TYPE_IMAGE;
        $this->status = (!empty($status)) ? $status : self::STATUS_NEUTRAL;
        $this->thumb = (empty($thumb)) ? $url : $thumb;
        $this->youTubeKey = $youTubeKey;
        $this->infoUrl = $infoUrl;
        $this->tags = (is_array($tags)) ? $tags : [];
        $this->nsfw = ($nsfw === true);
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
            'memeId' => $this->memeId,
            'displayName' => $this->displayName,
            'status' => $this->status,
            'url' => $this->url,
            'type' => $this->type,
            'selected' => $this->selected,
            'thumb' => $this->thumb,
            'youTubeKey' => $this->youTubeKey,
            'infoUrl' => $this->infoUrl,
            'tags' => $this->tags,
            'nsfw' => $this->nsfw
        ];
    }

    /**
     * @param $aMeme
     */
    public function initFromArray($aMeme)
    {
        $this->memeId = (isset($aMeme['memeId'])) ? $aMeme['memeId'] : null;
        $this->displayName = (isset($aMeme['displayName'])) ? $aMeme['displayName'] : null;
        $this->status = (isset($aMeme['status'])) ? $aMeme['status'] : null;
        $this->url = (isset($aMeme['url'])) ? $aMeme['url'] : null;
        $this->type = (isset($aMeme['type'])) ? $aMeme['type'] : null;
        $this->selected = (isset($aMeme['selected'])) ? $aMeme['selected'] : null;
        $this->thumb = (isset($aMeme['thumb'])) ? $aMeme['thumb'] : null;
        $this->youTubeKey = (isset($aMeme['youTubeKey'])) ? $aMeme['youTubeKey'] : null;
        $this->infoUrl = (isset($aMeme['infoUrl'])) ? $aMeme['infoUrl'] : null;
        $this->tags = (isset($aMeme['tags'])) ? $aMeme['tags'] : [];
        $this->nsfw = (isset($aMeme['nsfw'])) ? $aMeme['nsfw'] : false;
    }

    /**
     * returns the specified number of memes
     * @param $count
     * @return Meme[]
     */
    public static function getMemes($count)
    {
        $result = [];
        try {
            $memeJson = file_get_contents('memes.json', true);
            //echo $memeJson;die;
            $memes = json_decode($memeJson, true);
            //echo $memes;die;
            foreach($memes['memes'] as $meme){
                $nsfw = (isset($meme['nsfw']) && $meme['nsfw']);
                $tags = (isset($meme['tags']) && is_array($meme['tags'])) ? $meme['tags'] : [];
                $result[] = new Meme($meme['memeId'], $meme['displayName'] , $meme['url'] , null , false,
                    $meme['thumb'] , $meme['youTubeKey'] , $meme['infoUrl'] , $tags, $nsfw);
            }
            //cull
            $iter = count($result) - $count;
            while($iter > 0){
                $cullIndex = rand(0, count($result)-1);
                unset($result[$cullIndex]);
                $result = array_values($result);
                $iter--;
            }
        }catch (\Exception $e){
            error_log('failed to load memes.jsom');
        }
        return $result;
    }
}