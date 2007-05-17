<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'stencil',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'stencil' ) ) {
	$menuHash = array(
		'package_name'  => STENCIL_PKG_NAME,
		'index_url'     => STENCIL_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:stencil/menu_stencil.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}
?>
