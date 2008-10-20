<?php
/**
* $Header: /cvsroot/bitweaver/_bit_stencils/BitStencil.php,v 1.11 2008/10/20 21:40:11 spiderr Exp $
* $Id: BitStencil.php,v 1.11 2008/10/20 21:40:11 spiderr Exp $
*/

/**
* Stencil class to illustrate best practices when creating a new bitweaver package that
* builds on core bitweaver functionality, such as the Liberty CMS engine
*
* @date created 2004/8/15
* @author spider <spider@steelsun.com>
* @version $Revision: 1.11 $ $Date: 2008/10/20 21:40:11 $ $Author: spiderr $
* @class BitStencil
*/

require_once( LIBERTY_PKG_PATH.'LibertyMime.php' );

/**
* This is used to uniquely identify the object
*/
define( 'BITSTENCIL_CONTENT_TYPE_GUID', 'bitstencil' );

class BitStencil extends LibertyMime {
	/**
	* Primary key for our mythical Stencil class object & table
	* @public
	*/
	var $mStencilId;

	/**
	* During initialisation, be sure to call our base constructors
	**/
	function BitStencil( $pStencilName=NULL, $pContentId=NULL ) {
		LibertyMime::LibertyMime();
		$this->mStencilId = $pStencilName;
		$this->mContentId = $pContentId;
		$this->mContentTypeGuid = BITSTENCIL_CONTENT_TYPE_GUID;
		$this->registerContentType(
		   	BITSTENCIL_CONTENT_TYPE_GUID, array(
				'content_type_guid'   => BITSTENCIL_CONTENT_TYPE_GUID,
				'content_description' => 'Stencil',
				'handler_class'       => 'BitStencil',
				'handler_package'     => 'stencil',
				'handler_file'        => 'BitStencil.php',
				'maintainer_url'      => 'http://www.bitweaver.org'
		));
		$this->mViewContentPerm  = 'p_stencil_read';
		$this->mCreateContentPerm  = 'p_stencil_create';
		$this->mUpdateContentPerm  = 'p_stencil_update';
		$this->mAdminContentPerm = 'p_stencil_admin';
	}

	/**
	* Load the data from the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function load() {
		if( $this->verifyId( $this->mStencilId ) || $this->verifyId( $this->mContentId ) ) {
			$lookupColumn = $this->verifyId( $this->mStencilId ) ? 'stencil_id' : 'content_id';
			$bindVars = array();
			$selectSql = $joinSql = $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mStencilId ) ? $this->mStencilId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "SELECT s.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				$selectSql
				FROM `".BIT_DB_PREFIX."stencils` s
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = s.`content_id` ) $joinSql
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON( uue.`user_id` = lc.`modifier_user_id` )
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )
				WHERE s.`$lookupColumn`=? $whereSql";
			$result = $this->mDb->query( $query, $bindVars );

			if( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mStencilId = $result->fields['stencil_id'];

				$this->mInfo['creator'] =( isset( $result->fields['creator_real_name'] )? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] =( isset( $result->fields['modifier_real_name'] )? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['parsed_data'] = $this->parseData();

				LibertyMime::load();
			}
		}
		return( count( $this->mInfo ) );
	}

	/**
	* Any method named Store inherently implies data will be written to the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	* This is the ONLY method that should be called in order to store( create or update )an stencil!
	* It is very smart and will figure out what to do for you. It should be considered a black box.
	*
	* @param array pParams hash of values that will be used to store the page
	*
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	*
	* @access public
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash )&& LibertyMime::store( $pParamHash ) ) {
			$table = BIT_DB_PREFIX."stencils";
			$this->mDb->StartTrans();
			if( $this->mStencilId ) {
				$locId = array( "stencil_id" => $pParamHash['stencil_id'] );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['stencil_store'], $locId );
			} else {
				$pParamHash['stencil_store']['content_id'] = $pParamHash['content_id'];
				if( @$this->verifyId( $pParamHash['stencil_id'] ) ) {
					// if pParamHash['stencil_id'] is set, some is requesting a particular stencil_id. Use with caution!
					$pParamHash['stencil_store']['stencil_id'] = $pParamHash['stencil_id'];
				} else {
					$pParamHash['stencil_store']['stencil_id'] = $this->mDb->GenID( 'stencils_stencil_id_seq' );
				}
				$this->mStencilId = $pParamHash['stencil_store']['stencil_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['stencil_store'] );
			}

			$this->mDb->CompleteTrans();
			$this->load();
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	* Make sure the data is safe to store
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	* This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
	* NOTE: This is a PRIVATE METHOD!!!! do not call outside this class, under penalty of death!
	*
	* @param array pParams reference to hash of values that will be used to store the page, they will be modified where necessary
	*
	* @return bool TRUE on success, FALSE if verify failed. If FALSE, $this->mErrors will have reason why
	*
	* @access private
	**/
	function verify( &$pParamHash ) {
		global $gBitUser, $gBitSystem;

		// make sure we're all loaded up of we have a mStencilId
		if( $this->verifyId( $this->mStencilId ) && empty( $this->mInfo ) ) {
			$this->load();
		}

		if( @$this->verifyId( $this->mInfo['content_id'] ) ) {
			$pParamHash['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( @$this->verifyId( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( @$this->verifyId( $pParamHash['content_id'] ) ) {
			$pParamHash['stencil_store']['content_id'] = $pParamHash['content_id'];
		}

		// check some lengths, if too long, then truncate
		if( $this->isValid() && !empty( $this->mInfo['description'] ) && empty( $pParamHash['description'] ) ) {
			// someone has deleted the description, we need to null it out
			$pParamHash['stencil_store']['description'] = '';
		} else if( empty( $pParamHash['description'] ) ) {
			unset( $pParamHash['description'] );
		} else {
			$pParamHash['stencil_store']['description'] = substr( $pParamHash['description'], 0, 200 );
		}

		if( !empty( $pParamHash['data'] ) ) {
			$pParamHash['edit'] = $pParamHash['data'];
		}

		// check for name issues, first truncate length if too long
		if( !empty( $pParamHash['title'] ) ) {
			if( empty( $this->mStencilId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this page.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
				}
			} else {
				$pParamHash['content_store']['title'] =( isset( $pParamHash['title'] ) )? substr( $pParamHash['title'], 0, 160 ): '';
			}
		} else if( empty( $pParamHash['title'] ) ) {
			// no name specified
			$this->mErrors['title'] = 'You must specify a name';
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	* This function removes a stencil entry
	**/
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."stencils` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			if( LibertyMime::expunge() ) {
				$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}

	/**
	* Make sure stencil is loaded and valid
	**/
	function isValid() {
		return( $this->verifyId( $this->mStencilId ) );
	}

	/**
	 * Generate a valid url for the Stencil
	 *
	 * @param	object	PostId of the item to use
	 * @return	object	Url String
	 */
	function getDisplayUrl( $pStencilId=NULL ) {
		$ret = NULL;
		if( empty( $pStencilId ) && !empty( $this ) ) {
			$pStencilId = $this->getField( 'stencil_id' );
		}
		global $gBitSystem;
		if( @BitBase::verifyId( $pStencilId ) ) {
			$ret = STENCIL_PKG_URL.'index.php?stencil_id='.$pStencilId;
		}
		return $ret;
	}

	/**
	* This function generates a list of records from the liberty_content database for use in a list page
	**/
	function getList( &$pParamHash ) {
		global $gBitSystem, $gBitUser;
		// this makes sure parameters used later on are set
		LibertyContent::prepGetList( $pParamHash );

		$selectSql = $joinSql = $whereSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		if( is_array( $pParamHash['find'] ) ) {
			// you can use an array of pages
			$whereSql .= " AND lc.`title` IN( ".implode( ',',array_fill( 0,count( $pParamHash['find'] ),'?' ) )." )";
			$bindVars = array_merge ( $bindVars, $pParamHash['find'] );
		} elseif( is_string( $pParamHash['find'] ) ) {
			// or a string
			$whereSql .= " AND UPPER( lc.`title` )like ? ";
			$bindVars[] = '%' . strtoupper( $pParamHash['find'] ). '%';
		}

		$query = "SELECT ts.*, lc.`content_id`, lc.`title`, lc.`data` $selectSql
			FROM `".BIT_DB_PREFIX."stencils` ts INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = ts.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$this->mDb->convertSortmode( $pParamHash['sort_mode'] );
		$query_cant = "select count(*)
				FROM `".BIT_DB_PREFIX."stencils` ts INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = ts.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql";
		$result = $this->mDb->query( $query, $bindVars, $pParamHash['max_records'], $pParamHash['offset'] );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			// generate output that can be copied and pasted into the textarea
			if( !empty( $pParamHash['get_usage'] )) {
				// extract all variables
				preg_match_all( "!\{{3}(\w*?)\}{3}!", $res['data'], $matches );
				$res['usage'] = "{{{$res['title']}|\n";
				foreach( $matches[1] as $match ) {
					$res['usage'] .= "|$match=\n";
				}
				$res['usage'] .= "}}";
			}
			$ret[] = $res;
		}
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		// add all pagination info to pParamHash
		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	function findByTitle( $pTitle, $pUserId=NULL, $pContentTypeGuid ) {
		global $gBitDb;
		$userWhere = '';
		$bindVars = array( $pTitle, $pContentTypeGuid );
		if( @BitBase::verifyId( $pUserId ) ) {
			$userWhere = " AND lc.`user_id`=?";
			array_push( $bindVars, $pUserId );
		}
		return $gBitDb->getOne( "SELECT lc.`content_id` FROM `".BIT_DB_PREFIX."liberty_content` lc WHERE lc.`title`=? AND lc.`content_type_guid`=? $userWhere", $bindVars );
	}
}

/* === Service Functions === */
function stencil_content_edit( $pObject=NULL ) {
	global $gBitSystem, $gBitSmarty;
	$stencils = new BitStencil();
	$listHash = array(
		'sort_mode' => 'title_asc',
		'get_usage' => TRUE,
	);
	$gBitSmarty->assign( 'stencilList', $stencils->getList( $listHash ));
}
?>
