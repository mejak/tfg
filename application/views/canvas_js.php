<script type="text/javascript">
var canvas = new fabric.Canvas('canvas');
canvas.backgroundColor = "#ffffff";
canvas.renderAll();
$(document).ready(function(){
    //add color picket to two fields
    $('.color_pick').each(function(){
        $(this).ColorPicker({
              onSubmit: function(hsb, hex, rgb, el) {
                  $(el).find('i').css('color', '#'+hex);
                  $(el).ColorPickerHide();
                  if($(el).attr('id') == 'fill_color')
                    change_fill_color('#'+hex);
                  else if($(el).attr('id') == 'stroke_color')
                    change_stroke_color('#'+hex);

              },
              onBeforeShow: function() {
                  $(this).ColorPickerSetColor(this.value);
              }
            });
    });
    
    //default background
    //change_background_Color('#ffffff');
    //change canvas background
    /*function change_background_Color(color){
        var rect = new fabric.Rect({
          left: -100,
          top: -100,
          fill: color,
          width: canvas.width + 200,
          height: canvas.height + 200,
          selectable : false
        });
        canvas.add(rect);
    }
    //change background color on click
    $('#chng_bck').click(function() {
        var color = $('#fill_color').val();
        if (color == '') 
            change_background_Color('#000000');
        change_background_Color('#' + color);
        return false;
    });*/
    //add image from url or upload picture
    function change_fill_color(color){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ 
            o.fill = color;
        });
         
        } else {
            canvas.getActiveObject().fill = color;
        }
        canvas.renderAll();
        return false;
    }
    function change_stroke_color(color){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){
            if(o.get('type')==="text") 
                o.strokeWidth = 0.5;
            else
                o.strokeWidth = 3;
            o.stroke = color;
        });
         
        } else {
            if(canvas.getActiveObject().get('type')==="text")
                canvas.getActiveObject().strokeWidth = 0.5;
            else
                canvas.getActiveObject().strokeWidth = 3;
            canvas.getActiveObject().stroke = color;
        }
        canvas.renderAll();
        return false;
    }
    $('#remove_obj').click(function (){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ canvas.remove(o) });
          canvas.discardActiveGroup().renderAll();
        } else {
          canvas.remove(canvas.getActiveObject());
        }
        canvas.renderAll();
        return false;
    });
    $('#send_backward').click(function(){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ canvas.sendBackwards(o) });
        } else {
          canvas.sendBackwards(canvas.getActiveObject());
        }
        canvas.renderAll();
        return false;
    });
    $('#send_back').click(function(){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ canvas.sendToBack(o) });
        } else {
          canvas.sendToBack(canvas.getActiveObject());
        }
        canvas.renderAll();
        return false;
    });

    $('#bring_forward').click(function(){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ canvas.bringForward(o) });
        } else {
          canvas.bringForward(canvas.getActiveObject());
        }
        canvas.renderAll();
        return false;
    });
    $('#bring_front').click(function(){
        if(canvas.getActiveGroup()){
          canvas.getActiveGroup().forEachObject(function(o){ canvas.bringToFront(o) });
        } else {
          canvas.bringToFront(canvas.getActiveObject());
        }
        canvas.renderAll();
        return false;
    });
    $('#add_img').click(function(){
        var btn = this;
        var allowd_ext = ['jpeg', 'jpg', 'gif', 'png', 'bmp'];
        var file_name = $('#canvas_img_file').val();
        var img_url = $('#img_url').val();
        if(file_name == '' && img_url == '') return false;
        if(file_name){
            var ext = file_name.split('.').pop();
            ext = ext.toLowerCase();
            if($.inArray(ext, allowd_ext) == -1){
                $('#canvas_img_file').siblings('label.error').text('<?php echo $this->lang->line('validation_invalid_file_type') ?>');
                return false;
            }else
                $('#canvas_img_file').siblings('label.error').text('');
            var formData = new FormData();
            formData.append('canvas_img_file', $('#canvas_img_file')[0].files[0]);
            formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash() ?>');
            $.ajax({
                url : '<?php echo base_url() ?>index.php/canvas/upload_canvas_image',
                type : 'POST',
                processData : false,
                contentType: false,
                dataType: 'text',  
                cache: false,
                data : formData,
                beforeSend : function(){
                    $('#canvas_img_preloader').removeClass('hide');
                },
                success : function(data){
                    var response_array =  $.parseJSON(data);
                    if(response_array.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>)
                        window.location.href = response_array.message;
                    else if(response_array.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?>){
                        fabric.Image.fromURL(response_array.message, function(oImg) {
                            canvas.add(oImg);
                            $('#canvas_img_preloader').addClass('hide');
                            $('#canvas_img_file').val('');
                        });
                    }
                }
            });
        }else{
            $.ajax({
                url : '<?php echo base_url() ?>index.php/canvas/google_image',
                type: 'POST',
                data : {
                image_url : img_url,
                <?php echo $this->security->get_csrf_token_name() ?> : '<?php echo $this->security->get_csrf_hash() ?>'
                },
                beforeSend : function(){
                    $('#canvas_img_preloader').removeClass('hide');
                },
                success: function (data){
                    var response_array =  $.parseJSON(data);
                    if(response_array.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>)
                        window.location.href = response_array.message;
                    else if(response_array.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?>){
                        fabric.Image.fromURL(response_array.message, function(oImg) {
                            canvas.add(oImg);
                            $('#canvas_img_preloader').addClass('hide');
                        });
                    }
                },
                error: function (){
  
                }
            });
        }
        return false;
    });
    //add rectangle
    $('#add_rect').click(function() {
        var color = $('#fill_color i').css('color');
        if(color == 'rgb(255, 255, 255)') color = 'rgb(0, 0, 0)';
        var rect2 = new fabric.Rect({
          left: 0,
          top: 0,
          fill: color,
          width: 350,
          height: 200
        });
        canvas.add(rect2);
        canvas.renderAll();
        return false;
    });
    //add circle
    $('#add_circle').click(function() {
        var color = $('#fill_color i').css('color');
        if(color == 'rgb(255, 255, 255)') color = 'rgb(0, 0, 0)';
        var circle = new fabric.Circle({
            radius: 100, fill: color, left: 0, top: 0
        });
        canvas.add(circle);
        return false;
    });
    //add ellipse
    /*$('#add_ellipse').click(function() {
        var color = '#' + $('#fill_color').val();
        if(color == '#') color = '#000000';
        var stroke_color = $('#stroke_color').val();
        var ellipse = new fabric.Ellipse({
          left: 0,
          top: 0,
          fill: color,
          rx: 100,
          ry: 50
        });
        if (stroke_color != '')
            ellipse.set({
              stroke: '#' + stroke_color,
              strokeWidth: 3
            });
        canvas.add(ellipse);
        return false;
    });*/
      //add triangle
    $('#add_triangle').click(function() {
       var color = $('#fill_color i').css('color');
        if(color == 'rgb(255, 255, 255)') color = 'rgb(0, 0, 0)';
        var triangle = new fabric.Triangle({
            width: 100, height: 100, fill: color, left: 0, top: 0
        });
        canvas.add(triangle);
        return false;
    });
      //add Line
    $('#add_line').click(function() {
        var color = $('#fill_color i').css('color');
        if(color == 'rgb(255, 255, 255)') color = 'rgb(0, 0, 0)';
        var line = new fabric.Line([50,50,250,50], { stroke: color, strokeWidth: 4 });
        canvas.add(line);
        return false;
    });
      //add text
    $('#add_text').click(function() {
        var text = $('#c_text').val();
        if (text == '') return false;
        var color = $('#fill_color i').css('color');
        if(color == 'rgb(255, 255, 255)') color = 'rgb(0, 0, 0)';
        var font_family = $('#font_family').val();
        if(font_family == '') font_family = 'Times New Roman';
        var c_text = new fabric.Text(text, {
            fontFamily : font_family,
            fill : color,
            fontSize : 20
        });
        canvas.add(c_text);
        return false;
    });

    $('#preview_canvas').click(function(){
        canvas.deactivateAll().renderAll();
        $('#preview_modal img').attr('src', canvas.toDataURL());
        $('#preview_modal').modal('show');
        return false;
    });

     //add text
    $('#resize_canvas').click(function() {
        var width = $('#canvas_width').val();
        var height = $('#canvas_height').val();
        if (width == '' || height == '') return false;
        width = parseInt(width);
        height = parseInt(height);
        canvas.setWidth(width) ;  
        canvas.setHeight(height);
        canvas.renderAll();
        return false;
    });

});
</script>