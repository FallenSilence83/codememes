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
     * @param $mods
     * @return Word[]
     */
    public static function getWords($count, $mods = [])
    {
        $result = [];
        $available_mods = ['famous'];
        $eval_mods = array_intersect($available_mods, $mods);
        $numMods = count($eval_mods);
        try {
            //6 cards per mod

            if($numMods > 4){
                throw new \Exception('Too many mods');
            }
            if($numMods > 0){
                $perModCount = floor($count * .25);
                $coreCount = $count - ($numMods * $perModCount);
            }else{
                $coreCount = $count;
            }

            $rawSelected = [];
            foreach($eval_mods as $mod){
                $modJson = file_get_contents('words-' . $mod . '.json', true);
                $modWords = json_decode($modJson, true);

                $modSelected = $usedIndex = [];
                $safety = 0;
                while(count($modSelected) < $perModCount && $safety < 200){
                    $safety++;
                    $randIndex = rand(0, count($modWords['words'])-1);
                    if(!in_array($randIndex, $usedIndex)){
                        $usedIndex[] = $randIndex;
                        $modSelected[] = $modWords['words'][$randIndex];
                    }
                }
                $rawSelected = array_merge($rawSelected, $modSelected);
            }

            $codeJson = file_get_contents('words.json', true);
            $coreWords = json_decode($codeJson, true);

            $coreSelected = $usedIndex = [];
            $safety = 0;
            while(count($coreSelected) < $coreCount && $safety < 200){
                $safety++;
                $randIndex = rand(0, count($coreWords['words'])-1);
                if(!in_array($randIndex, $usedIndex)){
                    $usedIndex[] = $randIndex;
                    $coreSelected[] = $coreWords['words'][$randIndex];
                }
            }
            $rawSelected = array_merge($rawSelected, $coreSelected);


            foreach($rawSelected as $index=>$sWord){
                $result[] = new Word($index+1, $sWord);
            }
        }catch (\Exception $e){
            error_log('failed to load words.jsom');
        }
        return $result;
    }
}