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
 * Implement mutidomain hooks for t3lib_page
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_t3libPageHooks implements t3lib_Singleton {

	/**
	 * Extend the Enable Field Method to make use of the
	 *
	 * @param array      $params Array of the given Parameter
	 * @param t3lib_pageSelect $ref calling Object
	 * @return string extra SQL to add
	 */
	public function addEnableColumnsHook( $params , $ref ){

		if ( $params['ctrl']['tx_multidomainpublishing_column'] && $params['ctrl']['tx_multidomainpublishing_column'] != '' ){

			$domainRecord = tx_multidomainpublishing_domainHelper::getCurrentDomainRecord();

			if ($domainRecord){
				
				$visibitySettings = '`' . $params['table'] . '`.`' . $params['ctrl']['tx_multidomainpublishing_column'] . '`';
				$domainRecordUid = (int)$domainRecord['uid'];
				$sqlCondition = $GLOBALS['TYPO3_DB']->listQuery($params['ctrl']['tx_multidomainpublishing_column'], $domainRecordUid, $params['table']);

				if ($domainRecord['tx_multidomainpublishing_mode']  == tx_multidomainpublishing_constants::MODE_DENY ){
					$sql = ' AND NOT ' . $sqlCondition ;
				} else { // tx_multidomainpublishing_constants::MODE_ALLOW
					$sql = ' AND ' .  $sqlCondition;
				}
				return ($sql);
			}
		}
		return;
	}
}
?>
