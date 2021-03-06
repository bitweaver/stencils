<?php
// $Header$
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once( '../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

require_once( STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

// Now check permissions to access this page
if( $gContent->isValid() ){
	$gContent->verifyUpdatePermission();
}else{
	$gContent->verifyCreatePermission();
}

if( isset( $_REQUEST['stencil']["title"] )) {
	$gContent->mInfo["title"] = $_REQUEST['stencil']["title"];
}

if( isset( $_REQUEST['stencil']["description"] )) {
	$gContent->mInfo["description"] = $_REQUEST['stencil']["description"];
}

if( isset( $_REQUEST["format_guid"] ) ) {
	$gContent->mInfo['format_guid'] = $_REQUEST["format_guid"];
}

if( isset( $_REQUEST['stencil']["edit"] ) ) {
	$gContent->mInfo["data"] = $_REQUEST['stencil']["edit"];
	$gContent->mInfo["no_cache"] = TRUE;
	$gContent->mInfo["parsed_data"] = $gContent->parseData();
}

// If we are in preview mode then preview it!
if( isset( $_REQUEST["preview"] ) ) {
	$gBitSmarty->assign('preview', 'y');
	$gContent->invokeServices('content_preview_function');
} else {
	$gContent->invokeServices( 'content_edit_function' );
}

// Check if the page has changed
if( !empty( $_REQUEST["save_stencil"] )) {
	if( $gContent->store( $_REQUEST['stencil'] )) {
		bit_redirect( $gContent->getDisplayUrl() );
	} else {
		$feedback['errors'] = $gContent->mErrors;
	}
}

$gBitSmarty->assign( 'feedback', !empty( $feedback ) ? $feedback : NULL );

// Display the template
$gBitSystem->display( 'bitpackage:stencil/edit_stencil.tpl', tra('Stencil') , array( 'display_mode' => 'edit' ));
?>
