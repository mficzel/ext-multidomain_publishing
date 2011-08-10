<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once (PATH_tslib . 'interfaces/interface.tslib_menu_filterMenuPagesHook.php');

/**
 * Description of class
 *
 * @author martin
 * 
 */
class tx_multidomainpublishing_hooks implements t3lib_Singleton, tslib_menu_filterMenuPagesHook { 
	
	/**
	 * @var array active Domain Record
	 */
	private static $domainRecord;
	
	private static function getDomainRecord(){
		if ( !self::$domainRecord ){
			$host = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
			$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'domainName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr( $host, 'sys_domain' ) . ' AND hidden=0' );
			self::$domainRecord = $domainRecord;
		}
		return self::$domainRecord;
	}
	
	/**
	 * Get the pagetype from the Domain record
	 * 
	 * @param array $params Array of the Parameters
	 * @param tslib_fe $tsfe calling TSFE Object
	 * @return string extra SQL to add 
	 */
	public function tslib_fe_checkAlternativeIdMethodsPostProcHook(array $params, tslib_fe $tsfe){
		
		$domainRecord = self::getDomainRecord();
		
		if ($domainRecord && $domainRecord['tx_multidomainpublishing_pagetype'] && $domainRecord['tx_multidomainpublishing_pagetype'] > 0 ){
			$params['pObj']->mergingWithGetVars( array('type' => $domainRecord['tx_multidomainpublishing_pagetype'] ) );
		}
		
		return;
	}
	
	
	/**
	 * Show a 404 if a page is not allowed on the active domain
	 * 
	 * @param array $params Array of the given Parameter
	 * @param tslib_fe $tsfe calling TSFE Object 
	 * @return void
	 */
	public function tslib_fe_contentPostProcAllHook(array $params, tslib_fe $tsfe){
		
		if ( $visibility = $tsfe->page['tx_multidomainpublishing_visibility'] ){
			if ( ! ($visibility == '' || $visibility == 0) ){
				$domainRecord = self::getDomainRecord();
				if (!$domainRecord){
					$tsfe->pageNotFoundAndExit("Page not found");
				}				
				$allowedDomainIds = t3lib_div::trimExplode(',',$visibility);
				if ( !in_array( $domainRecord['uid'], $allowedDomainIds) ){
					$tsfe->pageNotFoundAndExit("Page not found");
				}
			}
		}
		
	}
	
	/**
	 * Extend the Enable Field Method to make use of the 
	 * 
	 * @param array      $params Array of the given Parameter
	 * @param t3lib_pageSelect $ref calling Object
	 * @return string extra SQL to add 
	 */
	public function t3lib_page_addEnableColumnsHook( array $params , t3lib_pageSelect $ref ){

		if ( $params['ctrl']['tx_multidomainpublishing_column'] && $params['ctrl']['tx_multidomainpublishing_column'] != '' ){
			$domainRecord = self::getDomainRecord();
			$columnName = '`' . $params['table'] . '`.`' . $params['ctrl']['tx_multidomainpublishing_column'] . '`';
			$domainId = (int)$domainRecord['uid'];
			$extraSqlConditions = ' AND (' . $columnName . ' = 0 OR ' . $columnName . '=' . $domainId . ' OR ' .$columnName . ' LIKE "'.$domainId.',%"  OR ' .$columnName . ' LIKE "%,'.$domainId.'" OR ' .$columnName . ' LIKE "%,'.$domainId.',%" )';
			return $extraSqlConditions;
		}
		
		return;
	}

	/**
	 * Checks if a page is OK to include in the final menu item array.
	 *
	 * @param	array		Array of menu items
	 * @param	array		Array of page uids which are to be excluded
	 * @param	boolean		If set, then the page is a spacer.
	 * @param	tslib_tmenu	The menu object
	 * @return	boolean		Returns true if the page can be safely included.
	 */
	public function tslib_menu_filterMenuPagesHook (array &$data, array $banUidArray, $spacer, tslib_menu $ref){
		
		if ( $visibility = $data['tx_multidomainpublishing_visibility'] ){
			if ($visibility == '' || $visibility == 0){
				return true; // visible for all
			} else {
				
				$domainRecord = self::getDomainRecord();
				if (!$domainRecord){
					return false;
				}
				
				$allowedDomainIds = t3lib_div::trimExplode(',',$visibility);
				if ( in_array( $domainRecord['uid'], $allowedDomainIds) ){
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
		
	}
}
?>
