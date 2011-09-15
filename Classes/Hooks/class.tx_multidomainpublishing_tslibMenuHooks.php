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
require_once (PATH_tslib . 'interfaces/interface.tslib_menu_filterMenuPagesHook.php');

/**
 * Implement mutidomain hooks for tslib_menu
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_tslibMenuHooks implements t3lib_Singleton, tslib_menu_filterMenuPagesHook { 
	
	/**
	 * an alias to method tslib_menu_filterMenuPagesHook, the interface seems to have been changed  
	 *
	 * @param	array		Array of menu items
	 * @param	array		Array of page uids which are to be excluded
	 * @param	boolean		If set, then the page is a spacer.
	 * @param	tslib_tmenu	The menu object
	 * @return	boolean		Returns true if the page can be safely included.
	 */
	public function processFilter(array &$data, array $banUidArray, $spacer, tslib_menu $ref){
		return $this->tslib_menu_filterMenuPagesHook(&$data, $banUidArray, $spacer, $ref);
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
		
		$domainRecord = tx_multidomainpublishing_domainHelper::getCurrentDomainRecord();
		$multidomainMode = $domainRecord['tx_multidomainpublishing_mode'];
		$selectedDomainIds = t3lib_div::trimExplode(',',$data['tx_multidomainpublishing_visibility'] );

		if ($multidomainMode == tx_multidomainpublishing_constants::MODE_DENY ) {
			if ( in_array( $domainRecord['uid'], $selectedDomainIds) ){
				return false;
			} else {
				return true;
			}
		} else { // tx_multidomainpublishing_constants::MODE_ALLOW 
			if ( in_array( $domainRecord['uid'], $selectedDomainIds) ){
				return true;
			} else {
				return false;
			}
		}
	}
	
}
?>
