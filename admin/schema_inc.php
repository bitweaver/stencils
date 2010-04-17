<?php
$tables = array(
	'stencils' => "
		stencil_id I4 AUTO PRIMARY,
		content_id I4 NOTNULL,
		description C(160)
	",
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( STENCIL_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( STENCIL_PKG_NAME, array(
	'description' => "Stencil package manages content templates.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array(
	'stencils_stencil_id_idx' => array('table' => 'stencils', 'cols' => 'stencil_id', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( STENCIL_PKG_NAME, $indices );

/*// ### Sequences
$sequences = array (
	'bit_stencil_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( STENCIL_PKG_NAME, $sequences );
*/


$gBitInstaller->registerSchemaDefault( STENCIL_PKG_NAME, array(
	//      "INSERT INTO `".BIT_DB_PREFIX."bit_stencil_types` (`type`) VALUES ('Stencil')",
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( STENCIL_PKG_NAME, array(
	array( 'p_stencil_admin', 'Can admin stencil', 'admin', STENCIL_PKG_NAME ),
	array( 'p_stencil_create', 'Can create a stencil', 'registered', STENCIL_PKG_NAME ),
	array( 'p_stencil_update', 'Can edit any stencil', 'editors', STENCIL_PKG_NAME ),
	array( 'p_stencil_read', 'Can read stencil', 'basic',  STENCIL_PKG_NAME ),
	array( 'p_stencil_remove', 'Can delete stencil', 'admin',  STENCIL_PKG_NAME ),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( STENCIL_PKG_NAME, array(
	array( STENCIL_PKG_NAME, 'stencil_list_title', 'y' ),
	array( STENCIL_PKG_NAME, 'stencil_list_description', 'y' ),
	array( STENCIL_PKG_NAME, 'stencil_list_stencils', 'y' ),
) );

// Requirements
$gBitInstaller->registerRequirements( STENCIL_PKG_NAME, array(
    'liberty' => array( 'min' => '2.1.4' ),
));
