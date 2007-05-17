<?php
// $Header: /cvsroot/bitweaver/_bit_stencils/admin/admin_stencil_inc.php,v 1.1 2007/05/17 16:55:55 spiderr Exp $
// Copyright (c) 2005 bitweaver Stencil
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// is this used?
//if (isset($_REQUEST["stencilset"]) && isset($_REQUEST["homeStencil"])) {
//	$gBitSystem->storeConfig("home_stencil", $_REQUEST["homeStencil"]);
//	$gBitSmarty->assign('home_stencil', $_REQUEST["homeStencil"]);
//}

require_once( STENCIL_PKG_PATH.'BitStencil.php' );

$formStencilLists = array(
	"stencil_list_stencil_id" => array(
		'label' => 'Id',
		'note' => 'Display the stencil id.',
	),
	"stencil_list_title" => array(
		'label' => 'Title',
		'note' => 'Display the title.',
	),
	"stencil_list_description" => array(
		'label' => 'Description',
		'note' => 'Display the description.',
	),
	"stencil_list_data" => array(
		'label' => 'Text',
		'note' => 'Display the text.',
	),
);
$gBitSmarty->assign( 'formStencilLists',$formStencilLists );

$processForm = set_tab();

if( $processForm ) {
	$stencilToggles = array_merge( $formStencilLists );
	foreach( $stencilToggles as $item => $data ) {
		simple_set_toggle( $item, 'stencils' );
	}

}

$stencil = new BitStencil();
$stencils = $stencil->getList( $_REQUEST );
$gBitSmarty->assign_by_ref('stencils', $stencils['data']);
?>
