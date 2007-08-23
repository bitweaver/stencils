<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/edit.php,v 1.3 2007/08/23 15:18:50 squareing Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_stencil_edit' );

require_once( STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

if( isset( $_REQUEST['stencil']["title"] )) {
	$gContent->mInfo["title"] = $_REQUEST['stencil']["title"];
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
$gBitSystem->display( 'bitpackage:stencil/edit_stencil.tpl', tra('Stencil') );
?>
