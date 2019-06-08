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