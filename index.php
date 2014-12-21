<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/unchi.css" />
		<title>Unchi - Gallery</title>
	</head>
	<body class="gallery">
		<div class="header">File host thing</div>
		<div class="menu top"><a href="upload.php">Upload</a> | <a href="?key=<?=@$_GET['key']?>">Gallery View (images only)</a> | <a href="?key=<?=@$_GET['key']?>&view=list">File Listing View</a></div>
		<div id="cont">
<?php
require 'config.php';

if(isset($_GET['key']) && hash('sha512', $_GET['key']) == $unchi['password']) {
	
	// Find out what page user is on and what view they have
	$page = (isset($_GET['page']) ? $_GET['page'] :                  1);
	$view = (isset($_GET['view']) ? $_GET['view'] : $unchi['def_view']);

	// Collect the images of the part of the page
	$pagepart = ($page-1) * $unchi['pg_amount'];
	
	// Get all the images from the img folder 
	$files = ($view == "list" ? preg_grep('/index\.php$/', glob('src/*.*'), PREG_GREP_INVERT) : ($view == "gallery" ? glob('src/*.{jpg,png,gif,jpeg}', GLOB_BRACE) : null));
	if($files == null) die("How did you even do this");
	
	// Sort the array by date and then count it
	array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
	$filecount = count($files);
	
	// Get the page amount
	$pgamnt = ceil($filecount / $unchi['pg_amount']);
	
	// Slice the array into the appropriate amount for pages
	$files = array_slice($files, $pagepart, $unchi['pg_amount']);
	
	// If view is gallery, display the image thumbnails
	if($view == "gallery") { 	
		foreach($files as $file) { 
			echo '			<a class="wow" title="'.date("d-m-y H:i:s", filemtime($file)).', '.(filesize($file) / 1000).' KB" style="background-image: url(\''.$unchi['thumb_dir'].explode(".", basename($file))[0].'.jpg\');" href="'.basename($file).'"></a>'."\n";
		}
	} 
	
	// If view is list, display the listing table
	else if($view == "list") {
		print("			<table>
				<tr>
					<th>Filename</th>
					<th>Date modified</th>
					<th>File size</th>
				</tr>");
			
		foreach($files as $file) {
			$base = basename($file);
			print('
				<tr>
					<td><a href="'.$base.'">'.$base.'</a></td>
					<td>'.date("d-m-y H:i:s", filemtime($file)).'</td>
					<td>'.(filesize($file) / 1000).' KB</td>
				</tr>');
		}
		
		print('
			</table>');
	}
	
	// Display the total files/images and page links 
	print('</div>
		<div class="menu bottom">
			Total: '.$filecount.' files. Pages:');
	
	// The for loop for displaying page links
	for ($i=1; $i<=$pgamnt; $i++) { 
		echo ($i == $page ? "\n".'			<i>'.$i.'</i>' : "\n".'			<a href="?key='.@$_GET['key'].'&page='.$i.'&view='.$view.'">'.$i.'</a>');
	}
	
}
	
	?>
	
		</div>
		<div id="footer">&copy; nookls 20141-12121.</div>
	</body>
</html>
