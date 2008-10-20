<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/edit.php,v 1.7 2008/10/20 21:40:11 spiderr Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

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
