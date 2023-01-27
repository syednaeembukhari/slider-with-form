<?php 
function sliderwithform_install()
{

	global $wpdb;

	$tbl = $wpdb->prefix.SWF_TBL;

	$myquery = "CREATE TABLE IF NOT EXISTS `".$tbl."` (
					`sliderid` bigint(20) NOT NULL AUTO_INCREMENT,
					`slidername` text NOT NULL,
					`slideremails` text NOT NULL,
					`created` varchar(100) NOT NULL,
					`createdby` varchar(25) NOT NULL,
					`status` varchar(25) NOT NULL DEFAULT 'Active',
					`last_update_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`sliderid`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;

				";

	$wpdb->query($myquery);

	$tblimg = $wpdb->prefix.SWF_IMGTBL;
	$myqueryimg = "CREATE TABLE IF NOT EXISTS `".$tblimg."` (
					`id` bigint(20) NOT NULL AUTO_INCREMENT,
					`sliderid` bigint(20)  NOT NULL,
					`imagepath` text NOT NULL,
					`created` varchar(100) NOT NULL,
					`createdby` varchar(25) NOT NULL,
					`status` varchar(25) NOT NULL DEFAULT 'Active',
					`last_update_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;

				";

	$wpdb->query($myqueryimg);

	//ALTER TABLE `wp_users_files` ADD `filecontents` TEXT NOT NULL 

	//ALTER TABLE `wp_swf_slider` ADD `brochure_eng` TEXT NOT NULL DEFAULT '' AFTER `last_update_on`, ADD `brochure_arb` TEXT NOT NULL DEFAULT '' AFTER `brochure_eng`;
	$myqueryupdate ="ALTER TABLE `".$tbl."` ADD `brochure_eng` TEXT NOT NULL DEFAULT '', ADD `brochure_arb` TEXT NOT NULL DEFAULT ''; " ;
	$wpdb->query($myqueryupdate);


}

add_action('activate_sliderwithform/sliderwithform.php', 'sliderwithform_install');

	

	



