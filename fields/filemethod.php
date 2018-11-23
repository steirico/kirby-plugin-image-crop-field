<?php

file::$methods['imageCrop'] = function($file) {
    $path = kirby()->roots()->thumbs() . DS . $file->page();
    $baseUrl = kirby()->urls()->thumbs() . DS . $file->page();
    $file =  str::replace($file->safeName(), '.'.$file->extension(), '') . '-cropped.' . $file->extension();
    return new Media($path . DS . $file, $baseUrl . DS . $file);
};

file::$methods['imageCropUrl'] = function($file) {
    return $file->imageCrop()->url();
};