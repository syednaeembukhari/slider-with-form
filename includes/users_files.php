<?php
global $wpdb;
$pffilename='';
$tbl = $wpdb->prefix."protected_files";
if(isset($_REQUEST['opt']))
{
	$fileid=$_REQUEST['id'];
	$status=0;
	if($_REQUEST['opt']=='download' )
	{
		$sql="SELECT * FROM ".$tbl." WHERE status='1' ORDER BY upload_date DESC";
		$usersfiles_down=$wpdb->get_results($sql);
		if(count($usersfiles_down)){
			foreach($usersfiles_down as $row)
			{
				$file=$row->physicalpath.'/'.$row->physicalname;
				
				if (file_exists($file)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.basename($file));
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					readfile($file);
					exit;
				}
				die();
			}
		}
		else
		$msg.="File Not found";	
	}
}

?>
<div class="pf-container">
<h1>Wholesaler Files</h1>
<div class="pf-bg">
<table width="100%" class="table table-hover">
  <tr>
    <th width="3%"><strong>#</strong></th>
    <th width="35%" align="left"><strong>File Name</strong></th>
    <th width="18%"  align="left"><strong>Upload Date</strong></th>
    <th width="21%"><strong>Download Link</strong></th>
  </tr>
  <?php $i=0;
  
  	
	$sql="SELECT * FROM ".$tbl." WHERE status='1' ORDER BY upload_date DESC";
	$usersfiles=$wpdb->get_results($sql);
	if(count($usersfiles)){
		foreach($usersfiles as $row)
		{
			if($row->filename!=''){
			$i++;
  ?>
  <tr>
    <td align="center"><?php echo $i;?></td>
    <td><?php echo $row->filename;?>&nbsp;</td>
    <td><?php echo $row->upload_date;?>&nbsp;</td>
    <td align="center">
    <a href="<?php echo $row->filelink;?>" >Download</a>
   <?php /*?> <a href="<?php echo get_admin_url().'admin.php?page=protected-files&opt=download&id='.$row->fileid;?>" title="click to download">Download</a></td>
   <?php */?>
  </tr>
  <?php 
			}
		}
  } ?>
</table>
</div>
</div>
