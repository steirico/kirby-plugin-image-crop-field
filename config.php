<?php

Kirby::plugin('steirico/kirby-plugin-image-crop-field', [
    'fileMethods' => [
        'croppedMedia' => function() {
            return CroppedImage::croppedImage($this);
        },
    ],
    'fields' => [
        'imagecrop' => [
            'props' => [
                'image' => function() {
                    return $this->model()->url();
                },
                'value' => function($value = []){
                    $method = kirby()->request()->method();
                    if(($method == "PATCH") || ($method == "POST")) {
                        new CroppedImage($this->model());
                    }

                    if(is_array($value)){
                        return $value;
                    } else {
                        return Data::decode($value, 'yaml');
                    }
                }
            ]
        ]
    ]
]);