<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author martin
 */
class tx_multidomainpublishing_hooks {

	function getPagetypeFromDomain($params, $ref){
		
		$host = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
		$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'domainName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr( $host, 'sys_domain' ) . ' AND hidden=0' );
		
		if ($domainRecord && $domainRecord['tx_multidomainpublishing_pagetype'] && $domainRecord['tx_multidomainpublishing_pagetype'] > 0 ){
			$params['pObj']->mergingWithGetVars( array('type' => $domainRecord['tx_multidomainpublishing_pagetype'] ) );
		}
		
		return;
	}
	
	function addDomainEnableFields($_funcRef, $_params, $ref){
		return;
	}
	
}
?>
