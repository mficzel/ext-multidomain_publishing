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
require_once (t3lib_extMgm::extPath('cms')  . 'layout/interfaces/interface.tx_cms_layout_tt_content_drawitemhook.php');

/**
 * Implement mutidomain hooks for tx_cms_layout
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage multidomain_publishing
 */
class tx_multidomainpublishing_txCmsLayoutHooks implements t3lib_Singleton, tx_cms_layout_tt_content_drawItemHook { 
	
	/**
	 * Preprocesses the preview rendering of a content element.
	 *
	 * @param	tx_cms_layout		$parentObject: Calling parent object
	 * @param	boolean				$drawItem: Whether to draw the item using the default functionalities
	 * @param	string				$headerContent: Header content
	 * @param	string				$itemContent: Item content
	 * @param	array				$row: Record row of tt_content
	 * @return	void
	 */
	public function preProcess(tx_cms_layout &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row){
		if ($row['tx_multidomainpublishing_visibility'] && $row['tx_multidomainpublishing_visibility'] != 0 ){
			
			$domainIds = explode (',',$row['tx_multidomainpublishing_visibility']);
			$domainInfos = array();
			foreach ($domainIds as $domainId){
				$domain =  tx_multidomainpublishing_domainHelper::getDomainRecordById($domainId);
				if ($domain){
					$modeInfo = ($domain['tx_multidomainpublishing_mode'] == 0) ? 'deny' : 'allow';   
					$nameInfo = $domain['domainName'];
					$domainInfos[] = $nameInfo . '-' . $modeInfo ;
				}
			} 
			
			if (count($domainInfos)>0){
				$itemContent .= '<strong>Domain Settings:</strong> ' . implode(', ',$domainInfos) . '<br/>';
			}
		} 		
	}
}
?>
