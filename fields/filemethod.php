<?php

file::$methods['imageCrop'] = function($requestedFile) {
    $path = kirby()->roots()->thumbs() . DS . $requestedFile->page();
    $baseUrl = kirby()->urls()->thumbs() . DS . $requestedFile->page();
    $file =  str::replace($requestedFile->safeName(), '.' . $requestedFile->extension(), '') . '-cropped.' . $requestedFile->extension();
    $filePath = $path . DS . $file;
    $fileUrl = $baseUrl . DS . $file;
    $media = new Media($filePath, $fileUrl);
    if($media->exists()){
        return $media;
    } else {
        return $requestedFile;
    }
};

file::$methods['imageCropUrl'] = function($requestedFile) {
    return $requestedFile->imageCrop()->url();
};