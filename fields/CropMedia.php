<?php
class CroppedMedia extends Media {
  use Kirby\Traits\Image;

  public $kirby;
  public $site;

  public function __construct($root, $url = null, $original) {
      parent::__construct($root, $url);
      
      $this->kirby = $original->kirby;
      $this->site  = $original->site;
  }
}