<?php
/*
Plugin Name:Admin Product Fields
Description:This custom plugin adds Admin custom fields on admin 
*/
?>

<?php
add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page() {
  add_menu_page( 'Product Fields', 'Product Fields', 'manage_options', 'product_fields', 'add_custom_fields', 'dashicons-welcome-widgets-menus', 20 );
  add_submenu_page( 'product_fields', 'listing Data', 'Listing Product',
	'manage_options', 'custom_field_data',"show_data_listing",$position = null);
 add_submenu_page(
      null, 
      'Edit page',
      'Edit page', 
      'manage_options', 
      'edit-page', 
      'edit_custom_fields'
     ); 
 add_submenu_page(
      null, 
      'Delete Page',
      'Delete Page', 
      'manage_options', 
      'delete-page', 
      'delete_custom_fields'
     );	 
}
function add_custom_fields(){
$brand_id = 948;
$cat_exist_error="";
$insert_msg="";
if(isset($_POST['save_custom_data'])){	
global $wpdb;
$brand_subcat = $_POST['selec_brand_subch'];
$product_content = $_POST['product_content_field'];
$file_url = $_POST['save_attachment_url'];
$get_data = "SELECT * FROM custom_fields_admin WHERE brand_sub_id=$brand_subcat";
$results_db = $wpdb->get_results($get_data);
if($results_db){
 $cat_exist_error = "<div class='category_existt'>This category is already exist</div>";
}
else{
 $insert_data = $wpdb->insert('custom_fields_admin', array(
    'brand_sub_id' => $brand_subcat,
    'product_content' => $product_content, 
	'file_url' => $file_url,
));
if($insert_data){
$insert_msg = "<div class='succes_msg_show'>Record updated_successfully</div>";
?>
<script>
setTimeout(function () {
location.reload();	
},5000);
</script>
<?php
} 	
}


}	

$termchildren = get_terms('product_cat',array('child_of' => $brand_id));


?>	
<div class="custom_admin_fields">
<form method="post">
<div class="single_form_fields">
<label>Select Brand</label>
<select name="selec_brand_subch">
<?php
foreach($termchildren as $termchildren1){
$term_cat_id = $termchildren1->term_id;
$term_namee = $termchildren1->name;
?>
<option value="<?php echo $term_cat_id;?>"><?php echo $term_namee;?></option>
<?php
}
?>
</select>
</div>
<div class="single_form_fields">
<label>Product Content</label>
<textarea name="product_content_field" class="product_textarea"></textarea>
</div>
<div class="single_form_fields">
<label>Select File Type</label>
<select name="video_or_img" class="imag_video">
<option value="">--Select File--</option>
<option value="image">Image</option>
<option value="video">Video</option>
</select>
</div>
<div class="single_form_fields input_text_content file_input_show">
<label>File Url</label>
<input type="text" name="save_attachment_url" class="save_attachment_url">
</div>

<div class="save_btn_sec">
<?php echo @$cat_exist_error;?>
<?php echo @$insert_msg;?>
<input type="submit" name="save_custom_data">
</div>
</form>
</div>

<?php	
}
add_action( 'admin_enqueue_scripts', 'admin_style_save' );
function admin_style_save() {
  wp_enqueue_script( 'jquery' );
  wp_register_style('custom_admin_css', plugins_url('/css/custom_field.css',__FILE__ ));
  wp_enqueue_style('custom_admin_css');
  wp_enqueue_script( 'custom_admin_js', plugins_url( '/js/custom_field.js', __FILE__ ));
}

function show_data_listing(){
global $wpdb;
$get_data1 = "SELECT * FROM custom_fields_admin";
$results_db1 = $wpdb->get_results($get_data1);
?>
<div class="show_custom_listing">
<table>
<tr>
<th>S.no</th>
<th>Brand Name</th>
<th>Content</th>
<th>File Type</th>
<th>File Link</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?php
$counter=1;
foreach($results_db1 as $results_db_get){
$row_id = $results_db_get->id;
$brand_sub_id = $results_db_get->brand_sub_id;
$product_content = $results_db_get->product_content;
$file_url_get = $results_db_get->file_url;
$file_type = $results_db_get->file_type;
$term_get = get_term( $brand_sub_id, 'product_cat');
?>
    <tr>
    <td><?php echo $counter;?></td>
	<td><?php echo $term_get->name;?></td>
	<td><?php echo $product_content;?></td>
	<td><span class="file_type_get"><?php echo $file_type;?></span></td>
	<td><?php if($file_type == "image"){?>
	<a target="blank" href="<?php echo $file_url_get;?>">
	<img src='<?php echo $file_url_get;?>'></a>
	<?php
	} 
	else { ?>
	<a target="blank" href="<?php echo $file_url_get?>"><video src='<?php echo $file_url_get;?>'></a>
	<?php }?></td>
	<td><a href="?page=edit-page&id=<?php echo $row_id;?>">Edit</a></td>
	<td><a href="?page=delete-page&id=<?php echo $row_id;?>">Delete</a></td>
    </tr>	
	<?php
$counter++;	
}

?>

</table>
</div>
<?php	
}

function edit_custom_fields(){
global $wpdb;
$rowid = $_GET['id'];
$cate_exist="";
if(isset($_POST['update_custom_data'])){  
$update_sub_id = $_POST['selec_brand_subch'];
$update_content = $_POST['product_content_field'];
$update_filetype = $_POST['video_or_img'];
$update_link = $_POST['save_attachment_url1'];
$table_name="custom_fields_admin";
//echo "UPDATE $table_name SET brand_sub_id=$update_sub_id and product_content='$update_content' and file_url='$update_link' and file_type='$update_filetype' WHERE id=$rowid";
$get_data1 = "SELECT * FROM custom_fields_admin WHERE brand_sub_id=$update_sub_id";
$results_db1 = $wpdb->get_results($get_data1);
$cat_exist_check = $results_db1[0]->brand_sub_id;
if($cat_exist_check == $update_sub_id){
$update_record1 = $wpdb->update( 
    'custom_fields_admin', 
    array( 
        'product_content'=>$update_content,
		'file_url' =>$update_link,
		'file_type' =>$update_filetype,
    ), 
    array( 'id' => $rowid )
);	
$cate_exist = "<div class='category_existt'>This category is already exist</div>";	
}
else{  
$update_record1 = $wpdb->update( 
    'custom_fields_admin', 
    array( 
        'brand_sub_id' => $update_sub_id, 
        'product_content'=>$update_content,
		'file_url' =>$update_link,
		'file_type' =>$update_filetype,
    ), 
    array( 'id' => $rowid )
);	
$updated_sucess="<div class='succes_msg_show'>Changes Update successfully";
}


}
$get_data2 = "SELECT * FROM custom_fields_admin where id=$rowid";
$results_db2 = $wpdb->get_results($get_data2);
$get_cat_idd = $results_db2[0]->brand_sub_id;
$product_content1 = $results_db2[0]->product_content;
$file_type2 = $results_db2[0]->file_type;
$file_urll2 = $results_db2[0]->file_url;
$brand_id = 948;
$termchildren = get_terms('product_cat',array('child_of' => $brand_id));
?>
<div class="custom_admin_fields">
<form method="post">
<div class="single_form_fields">
<label>Select category</label>
<select name="selec_brand_subch">
<?php
foreach($termchildren as $termchildren1){
$term_cat_id = $termchildren1->term_id;
$term_namee = $termchildren1->name;
?>
<option value="<?php echo $term_cat_id;?>" <?php if($term_cat_id == $get_cat_idd){?> selected="selected" <?php } ?>><?php echo $term_namee;?></option>
<?php
}
?>
</select>
</div>
<div class="single_form_fields">
<label>Product Content</label>
<textarea name="product_content_field" class="product_textarea"><?php echo $product_content1;?></textarea>
</div>
<div class="single_form_fields">
<label>Select File Type</label>
<select name="video_or_img" class="edit_imag_video">
<option value="">--Select File--</option>
<option value="image" <?php if($file_type2 == "image"){?> selected="selected" <?php }?>>Image</option>
<option value="video" <?php if($file_type2 == "video"){?> selected="selected" <?php }?>>Video</option>
</select>
</div>
<div class="single_form_fields input_text_content">
<input type="text" name="save_attachment_url1" class="save_attachment_url save_edit_attach" value="<?php echo $file_urll2;?>">
</div>
<?php echo @$cat_exist_error;?>
<div class="save_btn_sec">
<?php echo @$cate_exist;?>
<?php echo @$updated_sucess;?>  
<input type="submit" name="update_custom_data">
</div>
</form>
</div>


<?php
}


function delete_custom_fields(){
global $wpdb;	
$get_id_row = $_GET['id'];
$table = 'custom_fields_admin';
$wpdb->delete( $table, array( 'id' => $get_id_row ) );
wp_redirect(site_url().'/wp-admin/admin.php?page=custom_field_data');
}