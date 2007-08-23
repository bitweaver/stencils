<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'stencil',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => LIBERTY_SERVICE_CONTENT_TEMPLATES
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'stencil' ) ) {
	$menuHash = array(
		'package_name'  => STENCIL_PKG_NAME,
		'index_url'     => STENCIL_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:stencil/menu_stencil.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );

	require_once( STENCIL_PKG_PATH.'BitStencil.php' );
	$gLibertySystem->registerService( LIBERTY_SERVICE_CONTENT_TEMPLATES, STENCIL_PKG_NAME, array(
		// functions
		'content_edit_function' => 'stencil_content_edit',

		// templates
		'content_edit_tab_tpl'  => 'bitpackage:stencil/service_edit_tab_inc.tpl',
	));
}
?>
