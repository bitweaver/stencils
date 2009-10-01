<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_stencils/remove_stencil.php,v 1.5 2009/10/01 14:17:05 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: remove_stencil.php,v 1.5 2009/10/01 14:17:05 wjames5 Exp $
 * @package stencil
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( STENCIL_PKG_PATH.'BitStencil.php');
include_once( STENCIL_PKG_PATH.'lookup_stencil_inc.php' );

$gBitSystem->verifyPackage( 'stencil' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( "No stencil indicated" );
}

$gBitSystem->verifyPermission( 'p_stencil_remove' );

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header ("location: ".BIT_ROOT_URL );
		die;
	} else {
		vd( $gContent->mErrors );
	}
}

$gBitSystem->setBrowserTitle( tra( 'Confirm delete of: ' ).$gContent->getTitle() );
$formHash['remove'] = TRUE;
$formHash['stencil_id'] = $_REQUEST['stencil_id'];
$msgHash = array(
	'label' => tra( 'Delete Stencil' ),
	'confirm_item' => $gContent->getTitle(),
	'warning' => tra( 'This stencil will be completely deleted.' ),
	'error' => tra('This cannot be undone!'),
);
$gBitSystem->confirmDialog( $formHash,$msgHash );

?>
