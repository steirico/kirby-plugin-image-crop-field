<?php

class ImageResize extends \Gumlet\ImageResize {

    const K64 = 65536;
    const MB = 1048576;
    const TWEAKFACTOR = 2.75;
    const INCREASE_SEC = 10;

    private static $runCount = 0;

    protected $imageInfo;
    protected $memoryLimit;

    public function __construct($filename) {
        $this->imageInfo = getimagesize($filename);
        $this->memoryLimit = self::getMemory();
        $this->setMemory();
        self::$runCount++;
        self::setExecutionTime();
        parent::__construct($filename);
    }

    /**
    * Inspired by corey34 seen at https://github.com/gumlet/php-image-resize/issues/55#issuecomment-437035599
    */
    public function setMemory() {
        if($this->memoryLimit == -1){
            return $this;
        } else {
            $w = max($this->imageInfo[0], $this->dest_w, $this->source_w, $this->original_w, 1);
            $h = max($this->imageInfo[1], $this->dest_h, $this->source_h, $this->original_h, 1);

            if (array_key_exists("channels", $this->imageInfo)) {
                $channels = $this->imageInfo['channels'];
            } else {
                $channels = 3;
            }

            $memoryNeeded = round(($w * $h * $this->imageInfo['bits'] * $channels / 8 + self::K64) * self::TWEAKFACTOR);

            $memoryUsage = memory_get_usage(true);
            $newLimit = $memoryUsage + $memoryNeeded;
 
            if ($newLimit > $this->memoryLimit) {
                $newLimit = ceil($newLimit / self::MB);
                ini_set( 'memory_limit', $newLimit . "M" );
                $this->memoryLimit = $newLimit;
                return $this;
            } else {
                return $this;
            }
        }
    }

    private static function setExecutionTime(){
        $val = (int)ini_get('max_execution_time');
        set_time_limit($val + self::$runCount * self::INCREASE_SEC);
    }

    private static function getMemory() {
        $val = ini_get('memory_limit');
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;

        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
    
        return $val;
    }
}