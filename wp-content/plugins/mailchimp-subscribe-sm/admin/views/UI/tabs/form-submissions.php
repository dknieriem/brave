<?php if ( ! defined( 'ABSPATH' ) ) exit;

if (function_exists('smfb_premExt_formBuilder_database_renderFormDataTable')) {
  echo smfb_premExt_formBuilder_database_renderFormDataTable($postId);
}else{
	echo "<h1> Please get Form Builder Database extension to access all the submissions. </h1>";
}

?>