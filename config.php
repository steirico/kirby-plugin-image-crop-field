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
                'value' => function($value = null) {
                    return $this->value;
                }
            ]
        ]
    ]
]);