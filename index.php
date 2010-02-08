<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/index.php,v 1.7 2010/02/08 21:27:25 wjames5 Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once( '../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_stencil_read' );



// doesn't seem to do much -- laetzer 25.09.2008 03:08
/* 
if( !isset( $_REQUEST['stencil_id'] ) ) {
	$_REQUEST['stencil_id'] = $gBitSystem->getConfig( "home_stencil" );
}

require_once( STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

$gContent->addHit();

// Display the template
$gBitSystem->display( 'bitpackage:stencil/stencil_display.tpl', tra( 'Stencil' ) , array( 'display_mode' => 'display' ));
*/



// pasted from list_stencils.php -- laetzer 25.09.2008 03:09
// create new stencil object
$stencil = new BitStencil();
$stencilsList = $stencil->getList( $_REQUEST );
$gBitSmarty->assign_by_ref( 'stencilsList', $stencilsList );

// getList() has now placed all the pagination information in $_REQUEST['listInfo']
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:stencil/list_stencils.tpl', tra( 'Stencil' ) , array( 'display_mode' => 'list' ));

?>
