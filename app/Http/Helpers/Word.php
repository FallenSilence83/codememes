<?php

namespace App\Http\Helpers;

/**
 * Class Word
 */
class Word implements \Serializable, \JsonSerializable
{
    /**
     * @var string
     */
    protected $wordId;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var bool
     */
    protected $nsfw;
    /**
     * @var bool
     */
    protected $selected;

    const STATUS_ORANGE = 'orange';
    const STATUS_BLUE = 'blue';
    const STATUS_NEUTRAL = 'neutral';
    const STATUS_RICK = 'rick';

    /**
     * Word constructor.
     * @param string $wordId
     * @param string $text
     * @param string $status
     * @param bool $selected
     * @param bool $nsfw
     */
    public function __construct($wordId = null, $text = null, $status = null, $selected = false, $nsfw = false)
    {
        $this->wordId = $wordId;
        $this->text = $text;
        $this->status = (!empty($status)) ? $status : self::STATUS_NEUTRAL;
        $this->selected = ($selected === true);
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
            'wordId' => $this->wordId,
            'text' => $this->text,
            'status' => $this->status,
            'selected' => $this->selected,
            'nsfw' => $this->nsfw
        ];
    }

    /**
     * @param $aWord
     */
    public function initFromArray($aWord)
    {
        $this->wordId = (isset($aWord['wordId'])) ? $aWord['wordId'] : null;
        $this->text = (isset($aWord['text'])) ? $aWord['text'] : null;
        $this->status = (isset($aWord['status'])) ? $aWord['status'] : null;
        $this->selected = (isset($aWord['selected'])) ? $aWord['selected'] : null;
        $this->nsfw = (isset($aWord['nsfw'])) ? $aWord['nsfw'] : false;
    }

    /**
     * returns the specified number of words
     * @param $count
     * @return Word[]
     */
    public static function getWords($count)
    {
        $result = [];
        try {
            $wordJson = file_get_contents('words.json', true);
            $words = json_decode($wordJson, true);

            $usedIndex = [];
            $selected = [];
            $safety = 0;

            if($words['words']){
                $words = $words['words'];
            }else{
                throw new \Exception('words file corrupted');
            }
            while(count($selected) < $count && $safety < 200){
                $safety++;
                $randIndex = rand(0, count($words)-1);
                if(!in_array($randIndex, $usedIndex)){
                    $usedIndex[] = $randIndex;
                    $selected[] = $words[$randIndex];
                }
            }
            foreach($selected as $index=>$sWord){
                $result[] = new Word($index+1, $sWord);
            }
        }catch (\Exception $e){
            error_log('failed to load words.jsom');
        }
        return $result;
    }
}