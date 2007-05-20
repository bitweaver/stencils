<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'stencil',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'stencil' ) ) {
	$menuHash = array(
		'package_name'  => STENCIL_PKG_NAME,
		'index_url'     => STENCIL_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:stencil/menu_stencil.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );

	function parse_stencil_data( $matches ) {
		static $gStencilObjects = array();
		$output = $matches[0];
		if( !empty( $matches[2] ) ) {
			$output = '';
			$templateVars = array();
			$templateName = $matches[1];
			if( empty( $gStencilObjects[$templateName] ) ) {
				if( $stencilContentId = BitStencil::findByTitle( $templateName, NULL, BITSTENCIL_CONTENT_TYPE_GUID ) ) {
					$gStencilObjects[$templateName] = new BitStencil( NULL, $stencilContentId );
					if( $gStencilObjects[$templateName]->load() ) {
						$output =  $gStencilObjects[$templateName]->getField( 'data' );
					}
				}
			}
			if( $lines = explode( '|', $matches[2] ) ) {
				foreach( $lines as $line ) {
					if( strpos( $line, '=' ) ) {
						list( $name, $value ) = split( '=', trim( $line ) );
						$templateVars[$name] = $value;
						$output = preg_replace( '/\{\{\{'.$name.'\}\}\}/', $value, $output );
					}
				}
			}
				
			// now need to do the substitution
		}
		return( $output );
	}

}
?>
