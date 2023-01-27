<?php
/*
Plugin Name: Slider With Form.
Plugin URI:  
Description: Create, edit, delete, manage  Sliders With  Forms (arabic and english).
Author: Syed Naeem Tariq
Version: 1.0.1.2
Author URI:  https://www.upwork.com/freelancers/~012db1b3731c7e9808
License: GPL2 or later
Text Domain: slider-with-form
*/
 
define( 'SWF_VERSION', '3.5.19' );
define( 'SWF_FILE', __FILE__ );
define( 'SWF_ROOT', __DIR__ );
define( 'SWF_ROOT_URI', plugins_url( '', __FILE__ ) );
define( 'SWF_ASSET_URI', SWF_ROOT_URI . '/assets' );
define( 'SWF_SLUG',    'slider-with-form' );
define( 'SWF_PATH',plugin_dir_path( __FILE__ ));
define( 'SWF_TBL','swf_slider');
define( 'SWF_IMGTBL','swf_slider_images');


include( SWF_PATH . 'includes/install.php');
include( SWF_PATH . 'includes/menus.php');

add_action( 'admin_init', 'swf_admin_init' );

function swf_admin_init() {
		
	//
	if ( current_user_can('manage_options') ){} else{
				unregister_post_type( 'review' );
				remove_menu_page( 'edit.php' );
				remove_menu_page( 'tools.php' );
				remove_menu_page( 'post-new.php' );
				remove_menu_page( 'edit.php?post_type=review' );
				remove_menu_page( 'edit.php?post_type=teachers' );
				remove_menu_page( 'admin.php?page=vc-general' );
				remove_menu_page( 'admin.php?page=vc-welcome' );
	}

	wp_register_style( 'sliderwithform_css', SWF_ROOT_URI.'/css/style.css'  );
	//wp_register_style( 'sliderwithform_datepickercss', SWF_ROOT_URI.'datepicker/datepicker.css');
	wp_enqueue_style( 'sliderwithform_css' );
	//wp_enqueue_style( 'licence_sanghar_datepickercss' );
	
	wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
  	wp_enqueue_style( 'jquery-ui' ); 

	wp_register_style( 'font-awesome-css',SWF_ROOT_URI.'/font-awesome/css/font-awesome.min.css');
	wp_enqueue_style( 'font-awesome-css' );



	wp_register_style( 'uploader-css',SWF_ROOT_URI.'/vendor/uploader/css/jquery.dm-uploader.css');
	wp_enqueue_style( 'uploader-css' );

	//wp_enqueue_script( 'uploader-script',  SWF_ROOT_URI.'/vendor/uploader/index.js');
	//wp_enqueue_script( 'uploader-script',  SWF_ROOT_URI.'/vendor/uploader/js/jquery.dm-uploader.js');
	 
	//wp_enqueue_script( 'uploader-script-c',  SWF_ROOT_URI.'/vendor/uploader/demo-ui.js');
	//wp_enqueue_script( 'uploader-script-d',  SWF_ROOT_URI.'/vendor/uploader/demo-config.php');
	//wp_enqueue_script( 'script',  SWF_ROOT_URI.'datepicker/index.js' , array ( 'jquery', 'jquery-ui-datepicker' ), 1.7, true);
	
}

function protected_files_loader(){ include( SWF_PATH . 'admin/userfiles.php');}
//function users_files(){ include( PFPATH . 'admin/userntries.php');} 
function load_admin_script(){
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts','load_admin_script');


function show_swf_slider($atts)
{
	global $wpdb;
	$tblimg = $wpdb->prefix.SWF_IMGTBL;
	$pffilename='';
	$atts = shortcode_atts(
		array(
		'sliderid' =>''  ,
		'lang' =>''  ,
		'js' =>''  ,
		), $atts, '' );
 
	$sliderid=$atts['sliderid'];
	$lang=$atts['lang'];
	$js=$atts['js'];
	$sql="SELECT * FROM ".$tblimg." WHERE sliderid='".$sliderid."'  ORDER BY sliderid DESC";
	$usersfiles=$wpdb->get_results($sql);
	if(!empty($usersfiles))
	{
		if($js=='yes' || $js==''){
		wp_register_style( 'fotorama-css', SWF_ROOT_URI.'/vendor/fotorama/fotorama.css');
  		wp_enqueue_style( 'fotorama-css' );
  		wp_enqueue_script( 'script',  SWF_ROOT_URI.'/vendor/fotorama/fotorama.js' , array ( 'jquery'), false, false); 
  		}
  		$slider='<div style="width:100%; position:relative">';
  		$slider='<div class=" '.($lang=='arb'?'swf_arabic':'').'" id="swf_slider-'.$sliderid.''.($lang=='arb'?'arb':'').'" style="width:100%; position:relative; ">';
		$slider.= '<div id="swf_slidercontainer-'.$sliderid.''.($lang=='arb'?'arb':'').'" class="fotorama blur"  data-nav="thumbs" data-loop="true" data-thumbwidth="180" data-thumbheight="130" data-arrows="always" >';
		$i=0;
		foreach ($usersfiles as $file){ $i++;
			//if($i==1)
			  $slider.='<img src="'.$file->imagepath.'">';
			//$slider.='<img src="'.$file->imagepath.'" style="background-image: url(\''.$file->imagepath.'\'); background-position: center; background-repeat: no-repeat;  background-size: cover;">';
			//  For lazyload
			//else
			//$slider.='<a href="'.$file->imagepath.'"></a>';
		}
		
		$slider.= ' </div>';
		//$slider.= ' </div>';

		$formenglish='';
		$formenglish.= '<div class="forms forms_'.$sliderid.'" style="position:absolute;z-index:9; top:0px; width:100%; height:100%;">
					<div  style="width:100%; position:relative; padding-top:5px">
					<div  class="formcontainer"   >
					<form id="swf_form_'.$sliderid.'" >
					<input type="hidden" name="lang" value="eng"/>
					<input type="hidden" name="action" value="swfAddCustomer"/>
					<input type="hidden" name="sliderid" value="'.$sliderid.'"/>
					';
		$formenglish.= '<div class="form-item"><div class="control-label">Your Name</div><input type="text" name="yourname" id="swf_yourname" class="form-control"/></div>';
		$formenglish.= '<div class="form-item"><div class="control-label">Your Email</div><input type="text" name="youremail" id="swf_youremail" class="form-control"/></div>';
		$formenglish.= '<div class="form-item"><div class="control-label">Country Code + Phone Number</div><input type="text" name="yourphone" id="swf_yourphone" class="form-control" /></div>';
		$formenglish.= '<div class="form-item" style="text-align:center; padding-top:0px">
						<input class="btn" type="button" name="save" value="Show" onclick="swf_form_submit(\''.$sliderid.'\',\'\')"
						id="submit_btn_'.$sliderid.'_"/>
						<div   type="button"  id="submiting_btn_'.$sliderid.'_" style="display:none" ><img src="'.SWF_ROOT_URI.'/css/loading.gif" style="height:40px"/> </div>
						
					</div><div class="form-item"><div class="msg"></div></div>';
		$formenglish.= ' </form>

		</div></div></div>';

		 



		$formarabic='';


		$formarabic.= '<div class="forms forms_'.$sliderid.'arb"   style="position:absolute;z-index:9; top:0px; width:100%; height:100%;">
					<div  style="width:100%; position:relative; padding-top:5px">
					<div  class="formcontainer" style="">
					<form id="swf_form_'.$sliderid.'arb" >
					<input type="hidden" name="lang" value="arb"/>
					<input type="hidden" name="action" value="swfAddCustomer"/>
					<input type="hidden" name="sliderid" value="'.$sliderid.'"/>
					';
		$formarabic.= '<div class="form-item"><div class="control-label">الاسم </div><input type="text" name="yourname" id="swf_yourname" class="form-control"/></div>';
		$formarabic.= '<div class="form-item"><div class="control-label"> البريد الإلكتروني </div><input type="text" name="youremail" id="swf_youremail" class="form-control"/></div>';
		$formarabic.= '<div class="form-item"><div class="control-label">كود الدولة + رقم هاتفك  </div><input type="text" name="yourphone" id="swf_yourphone" class="form-control" /></div>';
		$formarabic.= '<div class="form-item" style="text-align:center;padding-top:0px">
						<input class="btn" type="button" name="save" value="اظهر" onclick="swf_form_submit(\''.$sliderid.'\',\'arb\')" id="submit_btn_'.$sliderid.'_arb"/>
						<div   type="button"  id="submiting_btn_'.$sliderid.'_arb" style="display:none" ><img src="'.SWF_ROOT_URI.'/css/loading.gif" style="height:40px"/> </div>
					</div>
					<div class="form-item"><div class="msg"></div></div>';
		$formarabic.= ' </form>
		</div>
		</div>
		</div> ';
		if($lang=='arb')
		{
			$slider.=$formarabic;	
		}else
		{
			$slider.=$formenglish;	
		}


		$slider.= ' </div>';
		$slider.= ' 
			<style>

				.blur  img { transition: filter 1s linear; }
				.blur img { filter: blur(20px); }
				.formcontainer{ width:320px; margin:2px auto; padding:8px; background:#FFFFFF;  border-radius:10px;min-height: 310px; border:1px solid #efefef;}
				.control-label{ line-height:1.5; font-size:14px; font-weight:bold; width:100%; height:28px; display:block;}
				.form-control{ padding:2px 8px;   width:100%; border:1px solid #999;
					border-radius:3px; margin-bottom: 10px !important;
				}
				.form-item{ margin:5px}
				.swf_arabic .form-item, .swf_arabic input, .swf_arabic label, .swf_arabic msg { text-align:right}
				.msg{color:#AA0000}
				.green{ color:#00AA00; font-weight:bold; font-size:24px; text-align:center}
				';
				if(!wp_is_mobile()){
				$slider.= ' 

				 @media only screen and (min-width:600px) {
   					.fotorama__arr{	width: 50px !important;
								height: 50px !important; 
								margin-top: -20px !important;
							}
					.fotorama__arr--next{background-position: -50px 0 !important;}
				} ';
				}
				if(wp_is_mobile()){
				$slider.= ' 
				@media only screen and (max-width:600px) {
   					.fotorama__arr{	width: 32px !important;
								height: 40px !important; 
								margin-top: -20px !important;
							}
					.fotorama__arr--next{background-position: -32px 0 !important;}
				}
				@media only screen and (max-width:1200px) {
   					.fotorama__arr{	width: 32px !important;
								height: 40px !important; 
								margin-top: -20px !important;
							}
					.fotorama__arr--next{background-position: -32px 0 !important;}
				} 
				';
			}

			$slider.= ' 	  
			</style>';


		return $slider;
	}else
	{
		return '';
	}
	
		//echo '<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">';
		//echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>';
		//print_r($usersfiles);

}
add_shortcode( 'swf_slider', 'show_swf_slider' );
function swf_enqueue() { 
 	$sliderid=0;
	if(isset($_REQUEST['sliderid']))
		$sliderid=$_REQUEST['sliderid'];
	wp_enqueue_script( 'ajax-script', SWF_ROOT_URI. '/js/my_query.js?version=1.2.2', array('jquery') );
    wp_localize_script( 'ajax-script','my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php?page='.SWF_SLUG.'&sliderid='.$sliderid)));
}
add_action( 'wp_enqueue_scripts', 'swf_enqueue' );


 

function swfAddCustomer() {

    global $wpdb;
	$tbl = $wpdb->prefix.SWF_TBL;
    $name = $_POST['yourname'];
    $phone = $_POST['yourphone'];
    $email = $_POST['youremail'];
    $sliderid = $_POST['sliderid'];
    $lang = $_POST['lang'];
    $msg='';
    if($name=='')
    {
    	
    	if($lang=='eng')
    		$msg ='Please, Enter Your Name';
    	else
    		$msg ='من فضلك ادخل اسمك';
    	 
    }
    if($phone=='')
    {
    	if($msg !='')
    		$msg .='<br/>';
    	if($lang=='eng')
    		$msg .='Please Enter a Your Phone No.';
    	else
    		$msg .='من فضكل ادخل رقم هاتفك';
    	
    }else
    {
    	if((int)$phone <= 0 ){
    		if($msg !='')
    			$msg .='<br/>';
    		
    		if($lang=='eng')
    			$msg .= 'Please Enter a Valid phone Number';
    		else
    			$msg .= 'من فضكل ادخل رقم هاتفك';
    	}
    }
    if($email!='')
    {
    	if(strpos($email, '@')===false){
    		if($msg !='')
    			$msg .='<br/>';
    		
    		if($lang=='eng')
    			$msg .='Enter a valid email address';
    		else
    			$msg .='تأكد من صحة البريد المرفق';
    	}
    }
    if($msg!='')
    {
    	echo $msg;
    	wp_die();
    }

	$sql="SELECT * FROM ".$tbl." WHERE sliderid='".$sliderid."'";
	$usersfiles=$wpdb->get_results($sql);
    if(!empty($usersfiles))
    {
    	$row=$usersfiles[0];
    	if($row->slideremails!='')
    	{
    		$subject  =  $name. "   Contact You through  [".$row->slidername."]";
    		$message  =  '<p><b>Name :</b> '.$name. "</p>";
    		$message .=  '<p><b>Email :</b> '.$email. "</p>";
    		$message .=  '<p><b>Phone :</b> '.$phone. "</p>";

    		$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			//$headers .= 'From: <webmaster@example.com>' . "\r\n";
    		if(strpos($row->slideremails, ',')===false)
    		{
    			$to=$row->slideremails;
    			wp_mail( $to, $subject, $message, $headers );
    		}else
    		{
    			$variable=explode(',',$row->slideremails);
    			foreach ($variable as $key => $to) {
    				// code...
    				wp_mail( $to, $subject, $message, $headers );
    			}
    		}
    		
    	}
    	

    	echo 1;
    	wp_die();
    }
    echo 0;
    wp_die();
}
add_action('wp_ajax_swfAddCustomer', 'swfAddCustomer');
add_action('wp_ajax_nopriv_swfAddCustomer', 'swfAddCustomer');




function saveUploadedFiles()
{
	global $wpdb;
	$pffilename='';
	$tbl = $wpdb->prefix.SWF_TBL;
	$tblimg = $wpdb->prefix.SWF_IMGTBL;
	$path=wp_upload_dir();  //print_r($path); die;
	$filebaseurl=$path['baseurl'];
	$fileuploadurl=$path['basedir'];
	
	$filename=basename($_FILES['file']['name']);
	$uploadpath=$fileuploadurl."/".$filename;
	$uploadurl_filename=$fileuploadurl."/".$filename;
	if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadurl_filename))
	{
		$picture=$filebaseurl.'/'.$filename;
		//$picture=$filename;
		$sql="INSERT INTO   `$tblimg`  ( `sliderid` ,`imagepath`,`created`,`createdby`,`status`) 
                    VALUES ('".$_REQUEST['sliderid']."','".$picture."','".date('Y-m-d H:i:s')."','0','Active')";	
		$wpdb->query($sql);
		$_SESSION['swfnotice'] = 'Slide Successfully added';
		 
		//$url= get_admin_url()."admin.php?page=".SWF_SLUG.'&opt=slides&sliderid='.$_REQUEST['sliderid'];
		//wp_redirect( $url );
		//echo '<script>window.location="'.$url.'";</script>';
		echo 1;
		wp_die(); 
	
	}else{
    	throw new RuntimeException('Failed to move uploaded file.');
    	wp_die(); 
  	}
}


add_action('wp_ajax_saveUploadedFiles', 'saveUploadedFiles');
add_action('wp_ajax_nopriv_saveUploadedFiles', 'saveUploadedFiles');


function swfLoadSliderInAjax()
{
	//echo  'slider return ';
	//echo 1;
	echo do_shortcode('[swf_slider sliderid="'.$_POST['sliderid'].'" lang="'.$_POST['lang'].'" js="no"]');
	echo "<script> 
	       jQuery('#swf_slidercontainer-".$_POST['sliderid'].$_POST['lang']."').fotorama({
	       	nav:'thumbs',
	       	loop:true,
	       	thumbwidth:180,
	       	width:'100%',  
	       	thumbheight:130, 
	       	arrows:'always' ,
	       	ratio:16/9
	       	}) ;
	      </script>

	      ";
	wp_die(); 
}
add_action('wp_ajax_swfLoadSliderInAjax', 'swfLoadSliderInAjax');
add_action('wp_ajax_nopriv_swfLoadSliderInAjax', 'swfLoadSliderInAjax');

function show_slider_ajax($atts)
{
	global $wpdb;
	$tblimg = $wpdb->prefix.SWF_IMGTBL;
	$tbl = $wpdb->prefix.SWF_TBL;
	$pffilename='';
	$atts = shortcode_atts(
		array(
		'sliderid' =>''  ,
		'lang' =>''  ,
		), $atts, '' );
 
	$sliderid=$atts['sliderid'];

	$sql="SELECT * FROM ".$tbl." WHERE  sliderid='".$sliderid."'";
	$usersfiles=$wpdb->get_results($sql);
	$row=(array)$usersfiles[0];
	 
	$lang=$atts['lang'];
	wp_register_style('fotorama-css'.$sliderid.$lang, SWF_ROOT_URI.'/vendor/fotorama/fotorama.css');
  	wp_enqueue_style('fotorama-css'.$sliderid.$lang );
  	wp_enqueue_script('script'.$sliderid.$lang,  SWF_ROOT_URI.'/vendor/fotorama/fotorama.js' , array ( 'jquery'), false, false); 
  	$brochtext='Full Brochure';
  	$brochtextid='the-full-brochure';
  	if($lang=='arb')
  	{
  		$brochtext=' البروشور الكامل';
  		$brochtextid='the-full-brochure';
  	} 
	$dataxx =  '<div style="width:100%"><div id="'.$brochtextid.'"></div><div id="sliderajax_'.$sliderid.$lang.'"> </div></div>
				<script> 
	         			setTimeout(function(){ swf_loadSliderAjax(\''.$sliderid.'\',\''.$lang.'\') }, 1000);';
	
	        			
		$dataxx.='	var button=\'<div class="brochurebtn "><div class="brochurebtn-holder"><a href="#'.$brochtextid.'" class="brochurelink" >'.$brochtext.'</a></div></div>\'; jQuery(\'body\').append(button);';
		 
	 
	/* 
	if($row['brochure_eng']!=''  ) {        			
		$dataxx.='	var button=\'<div class="brochurebtn "><div class="brochurebtn-holder"><a href="'.$row['brochure_eng'].'" target="_blank">'.$brochtext.'</a></div></div>\'; jQuery(\'body\').append(button);';
		}
	if($row['brochure_arb']!=''  ) {        			
		$dataxx.='	var buttonarb=\'<div class="brochurebtn "><div class="brochurebtn-holder arb"><a href="'.$row['brochure_arb'].'" target="_blank">'.$brochtext.'</a></div></div>\'; jQuery(\'body\').append(buttonarb);';
		}

	 */

		$leftp='5px';
		$bottomp='bottom:66px !important;';
		if(wp_is_mobile()){
			$leftp='106px';
			$bottomp='bottom:16px !important;';
		}
	$dataxx.='    </script><style>
	      .brochurebtn{position:fixed;'.$bottomp.'z-index: 9999999;   text-align:center; left:'.$leftp.' !important;}
	      .brochurebtn-holder {  }
	      .brochurebtn-holder a {  background:#b78f00; color:#FFFFFF; padding: 4px 12px; border-radius:5px; font-size: 20px; }
	      /*.contact-icons{right:5px; left:}
	      .call-icon{position: absolute; bottom: 0px;}*/
	      </style>';

$dataxx.=' <script>
jQuery(document).ready(function(){
  // Add smooth scrolling to all links
  jQuery(".brochurelink").on(\'click\', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
       
      jQuery(\'html, body\').animate({
        scrollTop: (jQuery(hash).offset().top - 140)
      }, 800, function(){
      	//window.location.hash = hash;
        // Add hash (#) to URL when done scrolling (default click behavior)
        jQuery(\'#header .bs-pinning-block\').removeClass(\'pinned\').addClass(\'unpinned\')
       
      });
    } // End if
  });
});
</script> ';



	return $dataxx;
	//wp_die(); 
}
add_shortcode( 'swf_slider_ajax', 'show_slider_ajax');






