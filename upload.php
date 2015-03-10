<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Upload - Unchi</title>
		<link rel="stylesheet" type="text/css" href="/unchi.css" />
	</head>
	<body class="upload">
		<div class="header">Unchi うんち</div>
		<div id="cont">
			Welcome to unchi! The file host that comes out of your anus.<br><br>
			<form method="POST" enctype="multipart/form-data">
				<input type="file" name="fileup" /><br>
				<input type="text" name="password" placeholder="Password" />
				<input type="submit" name="submit" value="Submit" />
			</form>
<?php
require 'config.php';

// Function for generating random filename
function generate_rand_name() {
	$file_rand_name   = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
	$file_exist_check = glob($unchi['upld_dir'].$file_rand_name.'.*');
	
	if(count($file_exist_check) == 0) 
		return $file_rand_name; 
	else 
		generate_rand_name();	
}

if(isset($_POST['submit'])) {
	
	if(@hash('sha512', $_POST['password']) == $unchi['password']) {

		// File information
		$file_name     = $_FILES['fileup']['name'];
		$file_tmp      = $_FILES['fileup']['tmp_name'];
		$file_size     = $_FILES['fileup']['size'];
		$file_ext      = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_basename = basename($file_name, $file_ext);
		
		// Disallowed file extensions
		$file_disallowed = array('php', 'html', 'htm', 'js', 'jsp', 'aspx');
		
		// Random generation of 5 alphanumeric chars for the new file name including check for existing name
		$file_rand_name = generate_rand_name();
	
		// Upload locations and shit
		$upload_file = $unchi['upld_dir'].$file_rand_name.'.'.$file_ext;
		
		if(!in_array($file_ext, $file_disallowed)) {
		
			if(move_uploaded_file($file_tmp, $upload_file)) {
				
				if(getimagesize($upload_file) !== false) {
					
					// Create image from file and get width and height
					$thumbnail   = ($file_ext == "png" ? imagecreatefrompng($upload_file) : ($file_ext == "jpg" || $file_ext == "jpeg" ? imagecreatefromjpeg($upload_file) : ($file_ext == "gif" ? imagecreatefromgif($upload_file) : "")));
					$orig_width  = imagesx($thumbnail);
					$orig_height = imagesy($thumbnail);
					
					// Create new widths and heights based on the aspect ratio
					$thumb_width  = ($orig_width > 160 ? 160 : $orig_width);
					$thumb_height = floor($orig_height * ($thumb_width / $orig_width));
					
					// Create new "temporary" image and copy thumbnail to it
					$thumb_tmp = imagecreatetruecolor($thumb_width, $thumb_height);
					imagecopyresampled($thumb_tmp, $thumbnail, 0, 0, 0, 0, $thumb_width, $thumb_height, $orig_width, $orig_height);
					
					// Create the image and destroy the original one
					imagejpeg($thumb_tmp, $unchi['thumb_dir'].$file_rand_name.'.jpg', 100);
					imagedestroy($thumbnail);
				
				}
				
				$upload_file_basename = basename($upload_file);
				echo '<span style="display:none">'.json_encode(array("filename" => $upload_file_basename))."</span>\n";
				echo 'Upload successful!<br>'.(getimagesize($upload_file) !== false ? '<a href="'.$upload_file_basename.'"><img src="'.$upload_file_basename.'" alt="Uploaded image"></a>' : '<a href="'.$upload_file_basename.'">'.$upload_file_basename.'</a>');
			
			} else {	
				echo 'Image failed to upload. PHP $_FILES error: '.$_FILES['fileup']['error'];
			}
			
		} else {	
			echo 'Invalid file type. Upload rejected';	
		}
		
	} else {
		echo 'Invalid password!!! Kill yourself.';
	}
	
} 
?>
		</div>
	</body>
</html>
