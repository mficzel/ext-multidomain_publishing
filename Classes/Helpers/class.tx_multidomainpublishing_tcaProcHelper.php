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

require_once (t3lib_extMgm::extPath('multidomain_publishing')  . 'Classes/Helpers/class.tx_multidomainpublishing_domainHelper.php');

/**
 * TCA Funtions for the multidomain_publishing extension
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_tcaProcHelper implements t3lib_Singleton { 

	public function selectDomainRestrictionsProcFunction($data, $tceForms) {
		$domainRecords = tx_multidomainpublishing_domainHelper::getDomainRecordsInRootlineOfPage($data['row']['uid'], array());
		$domainRecordUids = array_keys($domainRecords);
		$normalizedItems = array();

		foreach ($data['items'] as $item) {
			if (in_array($item[1], $domainRecordUids)) {
				if ($domainRecords[$item[1]]['tx_multidomainpublishing_mode'] == 0) {
					$mode = 'Don\'t show in ';
				} else {
					$mode = 'Show in ';
				}

				$item[0] = $mode.$item[0];
				$normalizedItems[] = $item;
			}
		}

		$data['items'] = $normalizedItems;
		return $data;
	}

}
?>
