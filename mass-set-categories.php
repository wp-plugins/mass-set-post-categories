<?php
/*
Plugin Name: Mass set Categories
Description: Set posts to categories all at once
Version: 0.1
Author: J. Frank Parnell
*/

function massSetCats () {
?>



<div class="wrap">
<h2>Mass Set Categories</h2>
</div>
<?php


$args = array(
    'numberposts'     => -1,
    'offset'          => 0,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'post',
    'post_status'     => 'publish' ); 
$posts = get_posts( $args );


$args = array(
	'type'                     => 'post',
	'child_of'                 => 0,
	'parent'                   => '',
	'orderby'                  => 'id',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );
$categories = get_categories( $args ); 
foreach($categories as $c){
//	msc_br($c->term_id);
//	msc_br($c->name);
//	msc_hr();
	$boxes[$c->term_id] = $c->name;
}
msc_br("All Your Categories");
msc_pra($boxes);



if($_POST['setcats']==true){
	//msc_pra($_POST);
	unset($_POST['setcats']);
	foreach ($_POST as $object_id => $cats ){
		$ids = array_keys($cats);
		 $ids = array_map('intval', $ids);
		 $ids = array_unique( $ids );
	 wp_set_post_categories( $object_id, $ids );//wp_set_post_categories( $post_ID, $post_categories ) //$post_categories (array) (optional) List of categories id numbers
		$ids = implode(', ',$ids);
		//msc_br($object_id.': '.$ids);
	
	}//foreach ( blah )
}//if setcats


?> 

<div style="background-color:#FFF; border:#036 2px solid; color:#000; font-family:Verdana,  font-size:12px;padding:10px;z-index:99"> 
Your posts:<br />
<form action="" enctype="multipart/form-data" method="post" >
	 count:&nbsp; <?=count($posts)?> <br><HR> 
<?php
	$fields=array('ID','post_title','category');
	foreach ($posts as $p ){
		$cats = getcategoriesforpost($p->ID , 'array');
		foreach ($fields as $f ){
			if($f=='category')continue;
			$r .= '<b>'.$f.'</b>'.':&nbsp;'.$p->$f."<br>";
		}//foreach ($fields as $f )
//		foreach($cats as $c){
//			$r .= '<b>name</b>'.':&nbsp;'.$c['name']."<br>";
//			$r .= '<b>id</b>'.':&nbsp;'.$c['term_id']."<br>";
//		}//foreach($cats as $c 
		foreach($boxes as $id=>$name){
			unset($check);unset($b1);unset($b2);
			foreach($cats as $c){
				if($c['term_id']==$id){$check = 'checked="checked"';$b1 = '<b>';$b2 = '</b>';} 
			}//foreach(getcategoriesforpost($p->ID
			$r .= '<input type="checkbox" name="'.$p->ID.'['.$id.']" '.$check.'  >'.$b1.$id.': '.$name.$b2."<br>";
		}//boxes as b
		$r .= '<input type="submit"  name="setcats" value="Submit" > - these all do the same, you dont have to submit for each post';
		$r .= '<hr>';
	}//foreach ( ($posts as $p ) )
	
	$r .= '</div>';
	 echo $r; 
	?>
 <input type="submit"  name="setcats" value="Submit" >   
<!-- <input type="submit"  name="asdf" value="this wont run the stuff" > -->  
 </form>   
<?php 
}//function massSetCats ()






function setMenuPage () {
//	  $page_title – the HTMLtitle tag of the new page (that appears between the <head> tags in HTML
		$page_title = 	 'Mass Set Categories';										   
//    $menu_title – the name that is going to appear in the menu
		$menu_title = 'Mass Set Categories';
//    $capability – who is going to have the right to access the page, in my case, if you have the right to manage options you will have the right to access this page
		$capability = 'manage_options';
//    $menu_slug – should be unique and will be part of the URL as a parameter, for example admin.php?page=wps_poll_admin
		$menu_slug = sanitize_title($menu_title )  ;
//    $function – the function that is called to run the plugin, 
		$function = 'massSetCats';
//    $icon_url – it’s optional and it ads an icon to the menu, to the left of the menu name
//    $position – optional as well, helps position the new menu amongst the standard menu entries.

add_menu_page($page_title ,  $menu_title,   $capability,  $menu_slug,  $function );
}

 add_action('admin_menu','setMenuPage');


//utility stuff
function msc_pra($ra){
	if(empty($ra))return;
	echo '<div style="background-color:#FFF; border:#036 2px solid; color:#000; font-family:Verdana, Geneva, sans-serif; font-size:12px;padding:10px;width:800px;position:static;z-index:99999;top:100px;left:100px">';
		echo "<PRE>";
			print_r($ra);
		echo "</pre>";
	echo '</div>';
	}
function msc_br($before=''){
	echo "$before
	<br />
	";
}
function msc_hr($before=''){
	echo "$before
	<HR>
	";
}


?>