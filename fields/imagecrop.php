<?php
class ImageCropField extends BaseField {

  static public $assets = array(
    'js' => array(
      'rcrop.js',
      'imagecrop.js'
    ),
    'css' => array(
      'rcrop.css',
      'imagecrop.css'
    )
  );

  public function content() {

    // field is used for pages
    if(!is_a($this->model(), 'File')) {
      return false;
    }

    $grid = new Brick('div');
    $grid->addClass('field-grid');


    if(is_array($this->minSize())){
      if(array_key_exists('w', $this->minSize())){
        $grid->data('min-w', $this->minSize()['w']);
      }

      if(array_key_exists('h', $this->minSize())){
        $grid->data('min-h', $this->minSize()['h']);
      }
    }

    $grid->data('aspect-ratio', $this->preserveAspectRatio() == false ? "false" : "true" );

    $grid->append('<div class="field-grid-item field-grid-item-1-2">' . $this->inputs("X") . '</div>');
    $grid->append('<div class="field-grid-item field-grid-item-1-2">' . $this->inputs("W") . '</div>');
    $grid->append('<div class="field-grid-item field-grid-item-1-2">' . $this->inputs("Y") . '</div>');
    $grid->append('<div class="field-grid-item field-grid-item-1-2">' . $this->inputs("H") . '</div>');

    return $grid;
  }

  public function inputs($name) {
    $content = new Brick('div');
    $content->addClass('field-content');

    $inputWrapper = new Brick('div', null);
    $field = new Brick('input', null);
    $field->addClass('input');
    $field->attr(array(
      'type'         => 'input',
      'value'        => $this->value()[$name],
      'required'     => true,
      'name'         => $name,
      'autocomplete' => 'off',
      'id'           => $name
    ));
    $icon = new Brick('div', null);
    $icon->addClass('field-icon');
    $icon->append('<span>' . $name . '</span>');
    $inputWrapper->append($field);
    $inputWrapper->append($icon);
    $content->append($inputWrapper);

    return $content;
  }

  private function getCroppedPath(){
    $file = $this->model();
    return kirby()->roots()->thumbs() . DS . $file->page();
  }

  private function getCroppedFile(){
    $file = $this->model();
    return str::replace($file->safeName(), '.' . $file->extension(), '') . '-cropped.' . $file->extension();
  }

  public function result() {
    $result = array();
    $result["W"] = get("W");
    $result["H"] = get("H");
    $result["X"] = get("X");
    $result["Y"] = get("Y");

    $croppedPath = $this->getCroppedPath();
    $croppedFileRoot = $croppedPath . DS .$this->getCroppedFile();

    if(!file_exists($croppedPath)){
      mkdir($croppedPath, 0770, true);
    }

    $image = new \Gumlet\ImageResize($this->model()->root());
    $image->freecrop($result["W"], $result["H"], $result["X"], $result["Y"]);
    $image->save($croppedFileRoot);

    if(is_array($this->targetSize())){
      if(array_key_exists('w', $this->targetSize())){
        $image = new \Gumlet\ImageResize($croppedFileRoot);
        $image->resizeToWidth($this->targetSize()['w'], $allow_enlarge = True);
        $image->save($croppedFileRoot);
      }

      if(array_key_exists('h', $this->targetSize())){
        $image = new \Gumlet\ImageResize($croppedFileRoot);
        $image->resizeToHeight($this->targetSize()['h'], $allow_enlarge = True);
        $image->save($croppedFileRoot);
      }
    }
    
    return yaml::encode($result);
  }


  public function value() {
    $value = parent::value();
    return yaml::decode($value); 
  }
}