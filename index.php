<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/index.php,v 1.2 2007/05/17 18:50:29 spiderr Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_stencil_read' );

if( !isset( $_REQUEST['stencil_id'] ) ) {
	$_REQUEST['stencil_id'] = $gBitSystem->getConfig( "home_stencil" );
}

require_once( STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

$gContent->addHit();

// Display the template
$gBitSystem->display( 'bitpackage:stencil/stencil_display.tpl', tra( 'Stencil' ) );
?>
