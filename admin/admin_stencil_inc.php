<?php
// $Header$

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

if( !empty( $_REQUEST['apply'] )) {
	$stencilToggles = array_merge( $formStencilLists );
	foreach( $stencilToggles as $item => $data ) {
		simple_set_toggle( $item, 'stencils' );
	}
}
?>
