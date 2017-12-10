jQuery(document).ready(function() {  
    
    jQuery('#post_preview_button').click(function() {  
     formfield = jQuery('#post_preview').attr('name');  
     tb_show('', 'media-upload.php?type=image&TB_iframe=true&ETI_field=post_preview');  
  
     window.send_to_editor = function(html) {  
     imgurl = jQuery('img',html).attr('src');  
     jQuery('input[name='+formfield+']').val(imgurl);  
     tb_remove();  
    }  
     return false;  
    });

});  
  
