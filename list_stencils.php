<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/list_stencils.php,v 1.3 2008/06/25 22:21:24 spiderr Exp $
// Copyright (c) 2004 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
require_once( STENCIL_PKG_PATH.'BitStencil.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'stencil' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_stencil_read' );

/* mass-remove:
	the checkboxes are sent as the array $_REQUEST["checked[]"], values are the wiki-PageNames,
	e.g. $_REQUEST["checked"][3]="HomePage"
	$_REQUEST["submit_mult"] holds the value of the "with selected do..."-option list
	we look if any page's checkbox is on and if remove_stencils is selected.
	then we check permission to delete stencils.
	if so, we call histlib's method remove_all_versions for all the checked stencils.
*/

if( isset( $_REQUEST["submit_mult"] ) && isset( $_REQUEST["checked"] ) && $_REQUEST["submit_mult"] == "remove_stencils" ) {

	// Now check permissions to remove the selected stencils
	$gBitSystem->verifyPermission( 'p_stencil_remove' );

	if( !empty( $_REQUEST['cancel'] ) ) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] ) ) {
		$formHash['delete'] = TRUE;
		$formHash['submit_mult'] = 'remove_stencils';
		foreach( $_REQUEST["checked"] as $del ) {
			$tmpPage = new BitStencil( $del);
			if ( $tmpPage->load() && !empty( $tmpPage->mInfo['title'] )) {
				$info = $tmpPage->mInfo['title'];
			} else {
				$info = $del;
			}
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>'.$info;
		}
		$gBitSystem->confirmDialog( $formHash, array( 'warning' => 'Are you sure you want to delete '.count( $_REQUEST["checked"] ).' stencils?', 'error' => 'This cannot be undone!' ) );
	} else {
		foreach( $_REQUEST["checked"] as $deleteId ) {
			$tmpPage = new BitStencil( $deleteId );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ) );
			}
		}
		if( !empty( $errors ) ) {
			$gBitSmarty->assign_by_ref( 'errors', $errors );
		}
	}
}

// create new stencil object
$stencil = new BitStencil();
$stencilsList = $stencil->getList( $_REQUEST );
$gBitSmarty->assign_by_ref( 'stencilsList', $stencilsList );

// getList() has now placed all the pagination information in $_REQUEST['listInfo']
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:stencil/list_stencils.tpl', tra( 'Stencil' ) , array( 'display_mode' => 'list' ));

?>
