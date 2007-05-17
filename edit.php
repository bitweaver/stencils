<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/edit.php,v 1.2 2007/05/17 18:50:28 spiderr Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_stencil_edit' );

require_once(STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

if( isset( $_REQUEST['stencil']["title"] ) ) {
	$gContent->mInfo["title"] = $_REQUEST['stencil']["title"];
}

if( isset( $_REQUEST['stencil']["description"] ) ) {
	$gContent->mInfo["description"] = $_REQUEST['stencil']["description"];
}

if( isset( $_REQUEST["format_guid"] ) ) {
	$gContent->mInfo['format_guid'] = $_REQUEST["format_guid"];
}

if( isset( $_REQUEST['stencil']["edit"] ) ) {
	$gContent->mInfo["data"] = $_REQUEST['stencil']["edit"];
	$gContent->mInfo['parsed_data'] = $gContent->parseData();
}

// If we are in preview mode then preview it!
if( isset( $_REQUEST["preview"] ) ) {
	$gBitSmarty->assign('preview', 'y');
	$gContent->invokeServices('content_preview_function');
}
else {
  	$gContent->invokeServices( 'content_edit_function' );
}

// Pro
// Check if the page has changed
if( !empty( $_REQUEST["save_stencil"] ) ) {

	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	if( $gContent->store( $_REQUEST['stencil'] ) ) {
		header( "Location: ".$gContent->getDisplayUrl() );
		die;
	} else {
		$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
	}
}

// Display the template
$gBitSystem->display( 'bitpackage:stencil/edit_stencil.tpl', tra('Stencil') );
?>
