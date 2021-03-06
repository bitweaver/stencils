<?php
/**
 * @version  $Header$
 * @package  liberty
 * @subpackage plugins_filter
 */

/**
 * definitions ( guid character limit is 16 chars )
 */

define( 'PLUGIN_GUID_FILTERSTENCIL', 'filterstencil' );

global $gLibertySystem, $gBitSystem;

$pluginParams = array(
	'title'              => 'Stencil',
	'description'        => 'If you are using the stencil package, you need to enable this filter.',
	'auto_activate'      => TRUE,
	'plugin_type'        => FILTER_PLUGIN,

	// filter functions
	'preplugin_function' => 'stencil_filter',
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_FILTERSTENCIL, $pluginParams );

/**
 * stencil_filter 
 * 
 * @param array $pData 
 * @param array $pFilterHash 
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function stencil_filter( &$pData, &$pFilterHash ) {
	global $gBitSystem, $gBitSmarty;
	if( $gBitSystem->isPackageActive( 'stencil' )) {
		require_once( STENCIL_PKG_PATH.'BitStencil.php' );
		$pData = preg_replace_callback( "/\{\{\/?([^|]+)(.*?)\}\}/s", 'stencil_parse_data', $pData );
	}
}

/**
 * stencil_parse_data 
 * 
 * @param array $pMatches 
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function stencil_parse_data( $pMatches ) {
	static $sStencilObjects = array();
	$output = $pMatches[0];
	if( !empty( $pMatches[2] )) {
		$output = '';
		$templateName = $pMatches[1];
		if( empty( $sStencilObjects[$templateName] ) ) {
			if( $stencilContentId = BitStencil::findByTitle( $templateName, NULL, BITSTENCIL_CONTENT_TYPE_GUID ) ) {
				$sStencilObjects[$templateName] = new BitStencil( NULL, $stencilContentId );
				if( $sStencilObjects[$templateName]->load() ) {
					$output = $sStencilObjects[$templateName]->getField( 'data' );
				}
			}
		} else {
			$output = $sStencilObjects[$templateName]->getField( 'data' );
		}

		if( $lines = explode( '|', $pMatches[2] )) {
			foreach( $lines as $line ) {
				if( strpos( $line, '=' ) ) {
					list( $name, $value ) = explode( '=', trim( $line ), 2 );
					// if the value is empty, we remove all the conditional stuff surrounding it
					if( empty( $value ) && !is_numeric( $value )) {
						$output = preg_replace( "!\{{3}$name>.*?<$name\}{3}!s", "", $output );
					} else {
						$pattern = array(
							"!\{{3}$name\}{3}!",
							"!\{{3}$name>!",
							"!<$name\}{3}!",
						);
						$replace = array( $value, "", "" );
						$output = preg_replace( $pattern, $replace, $output );
					}
				}
			}
			
			
			// any remaining {{{vars}}} will be removed
			$pattern = array(
				"!\{{3}\w+?>.*?<\w+\}{3}!s",
				"!\{{3}\w+?\}{3}!",
			);
			$output = preg_replace( $pattern, "", $output );
			
			//Process conditional statements ie. "{{#functionName}}"
			$output = preg_replace_callback("/{{#.*?}}/",'post_process',$output );
		}
	}
	return( $output );
}

function post_process( $pData ){
	$ret = '';
	$matches = array();
	if( preg_match_all( '/{{#if:[^|]*|}}/', $pData['0'], $matches ) ) {
		//This is an if statement in mediawiki syntax
		//{{#if:condition|result if true|result if false}}
		//remove the if, colon, and ending brackets to be left with 'condition|result if true|result if false' so that we can easily split
		$pData['0'] = substr($pData['0'],strpos($pData['0'],':')+1);
		$pData['0'] = substr($pData['0'],0,strpos($pData['0'],'}}'));
		$tokens = explode('|',$pData['0']);
		//token[0] is the checked variable
		//token[1] is case for true
		//token[2] is case for false
		if(!empty($tokens[0])){
			$ret = $tokens[1];
		}else{
			$ret = $tokens[2];
		}
	}
	return $ret;
}
?>
