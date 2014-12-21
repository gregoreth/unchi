<?php
/* Unchi config file v2.1 - (c) nookls
 * This is where things such as the password will be defined.
 * This file should not be moved to anywhere else. */
 
$unchi = array();

$unchi['password']  = ""; // sha512 hash of password
$unchi['upld_dir']  = "src/"; // Upload dir for images, files, etc. Should have a / at the end.
$unchi['thumb_dir'] = "src/thumb/"; // Location for storing thumbnails.
$unchi['mem_limit'] = "1000M"; // Temporary memory limit while uploading. I recommend making it ridiculously high just in case.
$unchi['def_view']  = "gallery"; // Default view to use in the gallery. Defaults to gallery (the image thumbnail mode)
$unchi['pg_amount'] = 100; // Amount of files to show per page (applies to both views)
