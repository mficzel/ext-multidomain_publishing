<?php
/***************************************************************
* Copyright notice
*
* (c) 2009 by n@work GmbH
*
* All rights reserved
*
* This script is part of the Multidomain Publishing extension. The 
* Multidomain Publishing extension is free software; you can redistribute
* it and/or modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 of the 
* License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This is a file of the multidomain_publishing project.
 * http://forge.typo3.org/projects/extension-multidomain_publishing
 *
 * Project sponsored by:
 * n@work GmbH - http://www.work.de
 *
 * $Id$
 */

require_once (t3lib_extMgm::extPath('multidomain_publishing')  . 'Classes/class.tx_multidomainpublishing_constants.php');
require_once (t3lib_extMgm::extPath('multidomain_publishing')  . 'Classes/Helpers/class.tx_multidomainpublishing_domainHelper.php');

/**
 * Implement mutidomain hooks for tslib_fe
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_tslibFeHooks implements t3lib_Singleton { 

	/**
	 * Get the pagetype from the Domain record
	 * 
	 * @param array $params Array of the Parameters
	 * @param tslib_fe $tsfe calling TSFE Object
	 * @return string extra SQL to add 
	 */
	public function checkAlternativeIdMethodsPostProcHook(array $params, tslib_fe $tsfe){
		
		$domainRecord = tx_multidomainpublishing_domainHelper::getCurrentDomainRecord();
		
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
	public function contentPostProcAllHook(array $params, tslib_fe $tsfe){
		
		$domainRecord = tx_multidomainpublishing_domainHelper::getCurrentDomainRecord();
		
		if (!$domainRecord){
			$tsfe->pageNotFoundAndExit("Domain not found");
			return;
		}
				
		$multidomainMode = $domainRecord['tx_multidomainpublishing_mode'];
		$selectedDomainIds = t3lib_div::trimExplode(',',$tsfe->page['tx_multidomainpublishing_visibility']);
		
		if ( $multidomainMode == tx_multidomainpublishing_constants::MODE_DENY ){
			if (in_array( $domainRecord['uid'],$selectedDomainIds ) ){
				$tsfe->pageNotFoundAndExit("Page not found");
			} 
		} else { // tx_multidomainpublishing_constants::MODE_ALLOW 
			if (!in_array( $domainRecord['uid'],$selectedDomainIds ) ){
				$tsfe->pageNotFoundAndExit("Page not found");
			}
		}
	}
}
?>
