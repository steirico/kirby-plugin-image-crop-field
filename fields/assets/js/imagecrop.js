var initImageCrop = function(){
    // get elements
    var $figure = $($.find('.fileview-image')[0]);
    var $image = $($figure.find('img')[0]);
    var $parent = $image.parent()
    var $wrapper = $('<div class="image-wrapper"></div>');
    var $field = $($.find('.field-name-crop')[0]);
    var $fieldGrid = $($field.find('.field-grid')[0]);
    var minW = parseInt($fieldGrid.attr('data-min-w'), 10);
    var minH = parseInt($fieldGrid.attr('data-min-h'), 10);
    var aspectRatio = $fieldGrid.attr('data-aspect-ratio');

    var inputs = {
        x : $($field.find('#X')),
        y : $($field.find('#Y')),
        width : $($field.find('#W')),
        height : $($field.find('#H'))
    };

    // setup wrapper
    $image.appendTo($wrapper);
    $parent.replaceWith($wrapper);

    // init rcrop
    $image.rcrop({
        minSize : [
            minW || 160,
            minH || 90
        ],
        preserveAspectRatio : aspectRatio,
        grid : true
    });

    function getCropValues(){
        var values = $image.rcrop('getValues');
        for(var key in inputs){
            inputs[key].val(values[key]);
        }
    }

    function setCropValues(trigger){
        var
            id = trigger ? trigger.target.id : "",
            x = inputs['x'].val(),
            y = inputs['y'].val(),
            width = inputs['width'].val(),
            height = inputs['height'].val(),
            settings = $image.rcrop('instance').settings;

        // TODO calc proper W and H on manual setting of new values
        if("W" == id){
            $image.rcrop('resize', width, false, x, y);
        } else if ("H" == id){
            $image.rcrop('resize', false, height, x, y);
        } else {
            $image.rcrop('resize', width, height, x, y);
        }
    }

    for(var key in inputs){
        inputs[key].on('focusout', setCropValues);
    }

    $image.on('rcrop-ready', setCropValues);
    $image.on('rcrop-changed', getCropValues);
};

$(document).ready(initImageCrop);
$(document).ajaxComplete(initImageCrop);