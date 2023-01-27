<?php
global $wpdb;
$pffilename='';
$tbl = $wpdb->prefix.SWF_TBL;
$tblimg = $wpdb->prefix.SWF_IMGTBL;

if(isset($_REQUEST['opt']) && $_REQUEST['opt']=='slides')
{
 	$sliderid=$_REQUEST['sliderid'];
	$sql="SELECT * FROM ".$tbl." WHERE  sliderid='".$sliderid."'";
	$usersfiles=$wpdb->get_results($sql);
	$row=$usersfiles[0];
	if(empty($row))
	{
		wp_redirect( get_admin_url()."admin.php?page=".SWF_SLUG ) ; exit;
	}


	//include_once( SWF_PATH . '/vendor/uploader/demo-config.php');

	//echo "<script> var swfuploadurl='".get_admin_url()."admin.php?page=".SWF_SLUG."&opt=slides&action=uploadsilde&sliderid=".$_REQUEST['sliderid']."'</script>"; 

	if($_REQUEST['action']=='uploadsilde')
	{

		$path=wp_upload_dir();  //print_r($path); die;
		$filebaseurl=$path['baseurl'];
		$fileuploadurl=$path['basedir'];
		
		$filename=date('dmyhis').basename($_FILES['picture']['name']);
		$uploadpath=$fileuploadurl."/".$filename;
		$uploadurl_filename=$fileuploadurl."/".$filename;
		if(move_uploaded_file($_FILES['picture']['tmp_name'],$uploadurl_filename))
		{
			$picture=$filebaseurl.'/'.$filename;
			//$picture=$filename;
			$sql="INSERT INTO   `$tblimg`  ( `sliderid` ,`imagepath`,`created`,`createdby`,`status`) 
	                    VALUES ('".$_REQUEST['sliderid']."','".$picture."','".date('Y-m-d H:i:s')."','0','Active')";	
			$wpdb->query($sql);
			$_SESSION['swfnotice'] = 'Slide Successfully added';
			 
			$url= get_admin_url()."admin.php?page=".SWF_SLUG.'&opt=slides&sliderid='.$_REQUEST['sliderid'];
			wp_redirect( $url );
			echo '<script>window.location="'.$url.'";</script>';
			exit; 
		
		}else{
	    throw new RuntimeException('Failed to move uploaded file.');
	  }
		//include_once( SWF_PATH . 'uploads.php');
	}else if($_REQUEST['action']=='deletesilde')
	{
		$sql="DELETE FROM  `$tblimg` WHERE id='".$_REQUEST['slideid']."'";	
			$wpdb->query($sql);
			$url=get_admin_url()."admin.php?page=".SWF_SLUG.'&opt=slides&sliderid='.$_REQUEST['sliderid'];
			wp_redirect( $url );
			echo '<script>window.location="'.$url.'";</script>';
			exit; 
	}
	?>
	<div class="wrap">

			<div class="pf-container">
	      <h1>Slider: <?php echo $row->slidername; ?> </h1>
	      <hr/>
	      <h2>Slides</h2>
	      <hr/>
	      <div class="">
	      	<?php 
	  			if($_SESSION['swfnotice']!=''){
						echo '<div class="notice notice-warning is-dismissible">  <p>'.$_SESSION['swfnotice'].'</p> </div>';
						$_SESSION['swfnotice']='';
     			}  
     			 
     			?>


    			<div class="row">
        		<div class="col-md-6 col-sm-6">
          	<div class="card"  style="width: 100%; max-width:none">
		            <div class="card-header">
	          	<!-- Our markup, the important part here! -->
	          	<form method="post" action="<?php echo get_admin_url()."admin.php?page=".SWF_SLUG.'&opt=slides&action=uploadsilde&sliderid='.$_REQUEST['sliderid'];?>" enctype="multipart/form-data">
		          <table>
		          	<tr>
		          		<td>
		          			<div id="drag-and-drop-zone" class="dm-uploader p-5">
		            

				            <div class="btn btn-primary btn-block mb-5">
				                <span>Select Image</span>
				                <input type="file" name="picture" title='Click to add Files' />
				            </div>
				          </div><!-- /uploader -->
		          		</td>
		          	</tr>
		          	<tr>
		          		<td>
		          				<input type="submit"  name="imguploader" value="Upload">
		          		</td>
		          	</tr>
		          </table>	
		         </form>
		         </div>
		         </div>
		        </div>
		        <div class="col-md-12 col-sm-12">
		          <div class="card"  style="width: 100%; max-width:none">
		             
		            <?php 
		            $sliderid=$_REQUEST['sliderid'];
								$sql="SELECT * FROM ".$tblimg." WHERE  sliderid='".$sliderid."'";
								$usersfiles=$wpdb->get_results($sql);
								
								?>
		             <table class="table" style="width: 100%">
		             	<thead>
		             		<tr>
		             			<th>Image</th>
		             			<th>Path</th>
		             			<th>Action</th>

		             		</tr>
		             	</thead>
		             	<?php foreach($usersfiles as $file){ ?>
		             	<tr>
		             		<td><img src="<?php echo $file->imagepath;?>" width="120px"></td>
		             		<td><?php echo $file->imagepath;?> </td>
		             		<td> <a href="javascript:void(0)" onclick="if(confirm('Do you want to delete?')){window.location='<?php  echo get_admin_url()."admin.php?page=".SWF_SLUG.'&opt=slides&action=deletesilde&sliderid='.$_REQUEST['sliderid'] ."&slideid=".$file->id; ?>'}">Delete</a></td>
		             	</tr>
		             <?php } ?>

		             </table> 
		          </div>
		        </div>
		      </div><!-- /file list -->


	      </div>
	    </div>
	  </div>


<?php 
}else{

	wp_redirect( get_admin_url()."admin.php?page=".SWF_SLUG ) ; exit;
}







