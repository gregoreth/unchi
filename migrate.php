<?php
/* Run-once thumbnail creation
 * This is mainly for migrating old images.
 * It could take a VERY long time to execute, depending on the number of images.
 * It's strongly recommended that the thumbnail folder is empty before you run this.
 * I would advice against deleting the file after you use it just in case, but PLEASE hide it or chmod 000 it.
 * There's no warning, the moment you open this it will run.
 * Does this even work lol? I've never tried it. */
 
require 'config.php'; 
 
set_time_limit(600);
$time_start = microtime(true); 
$files 		= glob('src/*.{jpg,png,gif,jpeg}', GLOB_BRACE);

foreach($files as $file) {

	// File information
	$file_ext      = pathinfo($file, PATHINFO_EXTENSION);
	$file_basename = basename($file, $file_ext);
	
	if(!file_exists($unchi['thumb_dir'].$file_basename.'.jpg')) { 
	
		// Create image from file and get width and height
		$thumbnail   = ($file_ext == "png" ? imagecreatefrompng($file) : ($file_ext == "jpg" || $file_ext == "jpeg" ? imagecreatefromjpeg($file) : ($file_ext == "gif" ? imagecreatefromgif($file) : "")));
		$orig_width  = imagesx($thumbnail);
		$orig_height = imagesy($thumbnail);
							
		// Create new widths and heights based on the aspect ratio
		$thumb_width  = ($orig_width > 160 ? 160 : $orig_width);
		$thumb_height = floor($orig_height * ($thumb_width / $orig_width));
							
		// Create new "temporary" image and copy thumbnail to it
		$thumb_tmp = imagecreatetruecolor($thumb_width, $thumb_height);
		imagecopyresampled($thumb_tmp, $thumbnail, 0, 0, 0, 0, $thumb_width, $thumb_height, $orig_width, $orig_height);
							
		// Create the image and destroy the original one
		imagejpeg($thumb_tmp, $unchi['thumb_dir'].$file_basename.'jpg', 100);
		imagedestroy($thumbnail);
		
	}
	
}

$time_end = microtime(true);
$total	  = $time_end - $time_start;
echo "Completed in $total seconds. Enjoy your thumbnails mom!";
