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

/**
 * Class to handle the reading of domain records
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_domainHelper {
	
	private static $domainRecord;
	private static $domainRecords;
	
	public static function getCurrentDomainRecord(){
		if ( !self::$domainRecord ){
			$host = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
			$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'domainName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr( $host, 'sys_domain' ) . ' AND hidden=0' );
			self::$domainRecord = $domainRecord;
		}
		return self::$domainRecord;
	}
	
	public static function getDomainRecordById($id){
		if (self::$domainRecords[$id]){
			return self::$domainRecords[$id];
		} 
		
		$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'uid=' . (int)$id . ' AND hidden=0' );
		if ($domainRecord){
			self::$domainRecords[(int)$id] = $domainRecord;
			return $domainRecord;
		}
		
		return NULL;
	}
	
}
?>
