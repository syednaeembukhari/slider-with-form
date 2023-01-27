<?php
global $wpdb;
$pffilename='';
$tbl = $wpdb->prefix.SWF_TBL;
$tblimg = $wpdb->prefix.SWF_IMGTBL;

if(isset($_REQUEST['opt']))
{
	$sliderid=$_REQUEST['sliderid'];
	$status=0;
	
	
	if($_REQUEST['opt']=='delete' )
	{
		
		$sql="DELETE FROM `$tbl`  WHERE sliderid='".$sliderid."'";
		$wpdb->query($sql);
		$sql="DELETE FROM `$tblimg`  WHERE sliderid='".$sliderid."'";
		$wpdb->query($sql);
		echo "<script>window.location='".get_admin_url()."admin.php?page=".SWF_SLUG."'</script>";
	}
	
	if($_REQUEST['opt']=='edit' )
	{
		if($_POST['swfform_update']=="swfform_update")
		{
			$sliderid=$_REQUEST['sliderid'];
			$slidername=$_POST['slidername'];
			$slideremails=$_POST['slideremails'];
			$userid=get_current_user_id();
		
		
			if($slidername=="")
				$msg.="Enter Slider Name<br/>";
			 
			if($slideremails==""){
				if($msg=="")
			 		$msg.='<br/>';
				$msg.="Enter email address<br/>";
			}
			
			$userid=get_current_user_id();

			if($msg=="")
			{
				$sql="UPDATE  `$tbl` SET  
					`slidername`='".$slidername."',  
					`slideremails`='".$slideremails."' 
					WHERE sliderid='".$sliderid."' ";	
				$wpdb->query($sql);
				

				$subdir=date('dmyhis');
				$path=wp_upload_dir();  //print_r($path); die;
				$filebaseurl=$path['baseurl'].'/'.$subdir;
				$fileuploadurl=$path['basedir'].'/'.$subdir;
				//$fileuploadurl=$path['basedir'];
				if((isset($_FILES['brochure_eng']['tmp_name']) && $_FILES['brochure_eng']['tmp_name']!='') ||
					(isset($_FILES['brochure_arb']['tmp_name']) && $_FILES['brochure_arb']['tmp_name']!=''))
				{ 
					if ( ! is_dir( $fileuploadurl) ) {
					    wp_mkdir_p($fileuploadurl );
					}
				}

				// eng brochure
				$brochure_eng='';
				if(isset($_FILES['brochure_eng']['tmp_name']) && $_FILES['brochure_eng']['tmp_name']!=''){
					$filename_eng=basename($_FILES['brochure_eng']['name']);
					$uploadurl_filename_eng=$fileuploadurl."/".$filename_eng;
					if(move_uploaded_file($_FILES['brochure_eng']['tmp_name'],$uploadurl_filename_eng))
					{
						$brochure_eng=$filebaseurl.'/'.$filename_eng;
						//$picture=$filename;
					}
				}
				if($brochure_eng!='')
				{
					$sql="UPDATE  `$tbl` SET  
								`brochure_eng`='".$brochure_eng."' 
								WHERE sliderid='".$sliderid."' ";	
					$wpdb->query($sql);
				}
				// arabic brochure
				$brochure_arb='';
				if(isset($_FILES['brochure_arb']['tmp_name']) && $_FILES['brochure_arb']['tmp_name']!=''){
					$filename_arb=basename($_FILES['brochure_arb']['name']);
					$uploadurl_filename_arb=$fileuploadurl."/".$filename_arb;
					
					if(move_uploaded_file($_FILES['brochure_arb']['tmp_name'],$uploadurl_filename_arb))
					{
						$brochure_arb=$filebaseurl.'/'.$filename_arb;
						//$picture=$filename;
					} 
				} 

				if($brochure_arb!='')
				{
					$sql="UPDATE  `$tbl` SET  
								`brochure_arb`='".$brochure_arb."' 
								WHERE sliderid='".$sliderid."' ";	
					$wpdb->query($sql);
				}
					
				$msg.="Slider  Successfully Updated";	
				$pffilename='';
			} // $msg
				
		} //swfform_update
	 
		
		$sliderid=$_REQUEST['sliderid'];
		$sql="SELECT * FROM ".$tbl." WHERE  sliderid='".$sliderid."'";
		$usersfiles=$wpdb->get_results($sql);
		$row=$usersfiles[0];
		
		?>
		<div class="wrap">
			<div class="pf-container">
	          <h1>Update  Slider</h1>
	          <div class="pf-bg">
	           <form action="" method="post" enctype="multipart/form-data">
	            <input type="hidden" value="swfform_update" name="swfform_update"/>
	            <table width="100%" border="0"> 
	              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Slider Name</strong> </label>
	                <input type="text" class="form-control" value="<?php echo $row->slidername;?>" name="slidername" placeholder=""/>
	                </div>
	                </td>
	                 
	            
	              </tr>
	              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Slider Emails</strong> </label>
	                <textarea   class="form-control" name="slideremails" placeholder="Email or Comma seprated emails list"><?php echo $row->slideremails;?></textarea>
	                </div>
	                </td>
	                 
	            
	              </tr>
	              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Brochure Eng</strong> </label>
	                 <input type="file"   class="form-control" name="brochure_eng" placeholder="Select brochure  in English"><?php echo $row->brochure_eng;?> 
	                </div>
	                </td>
	                 
	            
	              </tr>
	              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Brochure Arabic</strong> </label>
	                <input type="file"   class="form-control" name="brochure_arb" placeholder="Select brochure  in arabic"><?php echo $row->brochure_arb;?> 
	                </div>
	                </td>
	                 
	            
	              </tr>
	              <tr>
							    <td  colspan="2">
							    	<div class="form-group">
							      	<input type="submit" value="Save" class="btn btn-info" />
							    	</div>
							    </td>
					    
					    
					  			</tr>
	               
	            </table>
	          </form>
	        </div>
	       
	        <?php if($msg!=''){?>
	        <div class="pf-bg pf-msg"><?php echo $msg;?></div>
	        <?php }?>
	        
				</div>
			</div>        
	  <?php	
	}
	else if($_REQUEST['opt']=='slides')
	{
				include( SWF_PATH . 'admin/userfiles-slides.php');
	}

}
else
{

	if($_POST['swfform_submit']=="swfform_submit")
	{
			 
		$slidername=$_POST['slidername'];
		$slideremails=$_POST['slideremails'];
		$userid=get_current_user_id();
		
		
		if($slidername=="")
			$msg.="Enter Slider Name<br/>";
		 
		if($slideremails==""){
			if($msg=="")
		 		$msg.='<br/>';
			$msg.="Enter email address<br/>";
		}

			
			 
			
		if($msg=="")
		{
			$subdir=date('dmyhis');
			$path=wp_upload_dir();  //print_r($path); die;
			$filebaseurl=$path['baseurl'].'/'.$subdir;
			$fileuploadurl=$path['basedir'].'/'.$subdir;
			//$fileuploadurl=$path['basedir'];
			 
			if ( ! is_dir( $fileuploadurl) ) {
			    wp_mkdir_p($fileuploadurl );
			}
			
			// eng brochure
			$brochure_eng='';
			if(isset($_FILES['brochure_eng']['tmp_name']) && $_FILES['brochure_eng']['tmp_name']!=''){
				$filename_eng=basename($_FILES['brochure_eng']['name']);
				$uploadurl_filename_eng=$fileuploadurl."/".$filename_eng;
				if(move_uploaded_file($_FILES['brochure_eng']['tmp_name'],$uploadurl_filename_eng))
				{
					$brochure_eng=$filebaseurl.'/'.$filename_eng;
					//$picture=$filename;
				}
			}
			// arabic brochure
			$brochure_arb='';
			if(isset($_FILES['brochure_arb']['tmp_name']) && $_FILES['brochure_arb']['tmp_name']!=''){
				$filename_arb=basename($_FILES['brochure_arb']['name']);
				$uploadurl_filename_arb=$fileuploadurl."/".$filename_arb;
				
				if(move_uploaded_file($_FILES['brochure_arb']['tmp_name'],$uploadurl_filename_arb))
				{
					$brochure_arb=$filebaseurl.'/'.$filename_arb;
					//$picture=$filename;
				} 
			}
				 
			 
	 	  $sql="INSERT INTO `$tbl` (`slidername`,`slideremails`,`createdby`,`created`,
								 `status` ,`brochure_eng`,`brochure_arb`  )
			
					VALUES(	'".$slidername."', '".$slideremails."','".$userid."', '".date('Y-m-d h:i:s')."',
							'Active', '".$brochure_eng."', '".$brochure_arb."')";	
			$wpdb->query($sql);
			$msg.="Slider  Successfully Added";	
			 
			
		}
	}


?>
			<div class="wrap">	
				<div class="pf-container">
          <h1>Add New  Slider</h1>
          <div class="pf-bg">
           <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" value="swfform_submit" name="swfform_submit"/>
            <table width="100%" border="0"> 
              <tr>
               <td  >
                <div class="form-group col-md-12">
                <label for="filename"><strong>Slider Name</strong> </label>
                <input type="text" class="form-control" value="" name="slidername" placeholder="Enter Slider name"/>
                </div>
                </td>
                 
            
              </tr>
              <tr>
               <td  >
                <div class="form-group col-md-12">
                <label for="filename"><strong>Slider Emails</strong> </label>
                <textarea   class="form-control" name="slideremails" placeholder="Email or Comma seprated emails list"></textarea>
                </div>
                </td>
                 
            
              </tr>
              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Brochure Eng</strong> </label>
	                 <input type="file"   class="form-control" name="brochure_eng" placeholder="Select brochure  in English"> 
	                </div>
	                </td>
	                 
	            
	              </tr>
	              <tr>
	               <td colspan="2">
	                <div class="form-group col-md-12">
	                <label for="filename"><strong>Brochure Arabic</strong> </label>
	                <input type="file"   class="form-control" name="brochure_arb" placeholder="Select brochure  in arabic"> 
	                </div>
	                </td>
	                 
	            
	              </tr>
              
               <tr>
    
						    <td  colspan="2">
						    <div class="form-group">
						      <input type="submit" value="Save" class="btn btn-info" />
						    </div>
						    </td>
				    
				    
				  			</tr>
            </table>
          </form>
        </div>
 
			 	<script>
			 
			 	jQuery(document).ready(function(e) {
				 	jQuery('.datepicker').datepicker({'dateFormat': "dd/mm/yy"});
			   	// jQuery('#valid_upto').dcalendarpicker();
					//	jQuery('#issue_date').dcalendarpicker();
					//	jQuery('#dob').dcalendarpicker();
					});
				
				</script>
				<?php if($msg!=''){?>
				<div class="pf-bg pf-msg"><?php echo $msg;?></div>
				<?php }?>
					<h2>Sliders</h2>
					<div class="pf-bg">
						<div class="table-responsive">
							<table class="table   table-hover">
						  	<thead  ">
						      <tr>
						        <th><strong>#</strong></th>
						        <th><strong>Slider Name</strong></th>
						        <th><strong>Slider Emails</strong></th>
						        <th><strong>Short Code</strong></th>
						        <th><strong>Slides</strong></th>
						         
						        <th><strong>Action</strong></th>
						      </tr>
						  	</thead>
						  	<tbody>
							  <?php $i=0;
							  
							  	
								$sql="SELECT * FROM ".$tbl." ORDER BY sliderid DESC";
								$sliders=$wpdb->get_results($sql);
								if(count($sliders)){
									foreach($sliders as $row)
									{
										$i++;
							  ?>
							  <tr>
							    <td align="center"><?php echo $i;?></td>
							     
							    <td align="center"><?php echo $row->slidername;?>&nbsp; </td>
							    <td align="center"><?php echo $row->slideremails;?>&nbsp;</td>
							    <td align="center">[swf_slider sliderid="<?php echo $row->sliderid;?>" lang=""]&nbsp;
							    	<br/>
							    	[swf_slider sliderid="<?php echo $row->sliderid;?>" lang="arb"]
							    	<br/>
							    	[swf_slider_ajax sliderid="<?php echo $row->sliderid;?>" lang=""] <br/>
							    	[swf_slider_ajax sliderid="<?php echo $row->sliderid;?>" lang="arb"]

							    </td>
							    <td align="center">
							    	<a href="<?php echo get_admin_url().'admin.php?page='.SWF_SLUG.'&opt=slides&sliderid='.$row->sliderid;?>"  title="Add / Edit Slides" style="color:#0A0; text-decoration:none; font-size:18px; line-height:18px">
							    	Add/Edit&nbsp;
							    	</a>
							    </td>
							    <td align="center">
										<a href="<?php echo get_admin_url().'admin.php?page='.SWF_SLUG.'&opt=edit&sliderid='.$row->sliderid;?>"  title="Edit Slider" style="color:#0A0; text-decoration:none; font-size:18px; line-height:18px"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
									 
										<a href="javascript:void(0)" onclick="if(confirm('Do you want to delete this slider and all of its slides? this action cannot be undone.')){window.location='<?php echo get_admin_url().'admin.php?page='.SWF_SLUG.'&opt=delete&sliderid='.$row->sliderid;?>';}"  title="Delete Slider" style="color:#A00; text-decoration:none; font-size:18px; line-height:18px"><i class="fa fa-times-circle-o"></i></a>
							    </td>
							  </tr>
						  <?php 
								}
						  } 
						  ?>
							</tbody>
						</table>
						</div>
					</div>

				</div>
			</div>
		<?php 
	}







