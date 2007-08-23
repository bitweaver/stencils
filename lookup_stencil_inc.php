<?php
global $gContent;
require_once( STENCIL_PKG_PATH.'BitStencil.php');
require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || !$gContent->isValid() ) {
	if( @BitBase::verifyId( $_REQUEST['stencil_id'] ) ) {
		// if stencil_id supplied, use that
		$gContent = new BitStencil( $_REQUEST['stencil_id'] );

	} elseif( @BitBase::verifyId( $_REQUEST['content_id'] ) ) {
		// if content_id supplied, use that
		$gContent = new BitStencil( NULL, $_REQUEST['content_id'] );

	} elseif (@BitBase::verifyId( $_REQUEST['stencil']['stencil_id'] ) ) {
		$gContent = new BitStencil( $_REQUEST['stencil']['stencil_id'] );

	} else {
		// otherwise create new object
		$gContent = new BitStencil();
	}

	$gContent->load();
	$gBitSmarty->assign_by_ref( "gContent", $gContent );
}
?>
