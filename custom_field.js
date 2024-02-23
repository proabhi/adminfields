jQuery(document).ready(function(){
	jQuery(document).on("change",".imag_video",function(){
		var get_cur_sel = jQuery(this).val();
		if(get_cur_sel == ""){
		jQuery('.file_input_show').hide();	
		}
		else{
		jQuery('.file_input_show').show();	
		}
		
		
	});  
	
   var edit_img_field = jQuery('.edit_imag_video').val();	
	if(edit_img_field){
	jQuery('.file_input_show .save_edit_attach').show();
	}
	else{
	jQuery('.file_input_show .save_edit_attach').hide();	
	}
	
	jQuery(document).on("change",".edit_imag_video",function(){
		jQuery('.file_input_show .save_edit_attach').val('');
		var get_cur_sel = jQuery(this).val();
		if(get_cur_sel == ""){
		jQuery('.save_attachment_url.save_edit_attach').hide();	
		}
		else{
		jQuery('.save_attachment_url.save_edit_attach').show();	
		}
		
		
	});
	
	
});

