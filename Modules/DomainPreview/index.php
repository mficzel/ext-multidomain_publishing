<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Thomas Hempel, Martin Ficzel <hempel@work.de, ficzel@work.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */
unset($MCONF);
require('conf.php');

require($BACK_PATH . 'init.php');
require($BACK_PATH . 'template.php');
$LANG->includeLLFile('EXT:multidomain_publishing/Modules/DomainPreview/locallang.xml');

$BE_USER->modAccess($MCONF, 1);

/**
 * Module 'Domain Preview' for the 'multidomain_publishing' extension.
 *
 * @author	Thomas Hempel, Martin Ficzel <hempel@work.de, ficzel@work.de>
 * @package	TYPO3
 * @subpackage	tx_multidomainpublishing
 */
class tx_multidomainpublishing_module1 extends t3lib_SCbase {

	// Internal, dynamic:
	var $pageinfo;
	var $fileProcessor;
	
	var $domainId;
	var $domainRecord;
	
	/**
	 * Document Template Object
	 *
	 * @var mediumDoc
	 */
	var $doc;
	
	function init()	{
		parent::init();
		$this->domainId = (int) $this->MOD_SETTINGS['function'];
		if ($this->domainId > 0){ 
			$this->domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'uid=' . $this->domainId . ' AND hidden=0');
		}	
	}	
		
			
	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return	void
	 */
	function menuConfig() {

		$domainRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'hidden=0');

		$this->MOD_MENU = Array(
			'function' => Array()
		);

		foreach ($domainRecords as $domainRecord) {
			$this->MOD_MENU['function'][$domainRecord['uid']] = $domainRecord['domainName'];
		}
		parent::menuConfig();
	}

	/**
	 * Create the panel of buttons for submitting the form or otherwise perform operations.
	 *
	 * @return	array	all available buttons as an assoc. array
	 */
	protected function getButtons()	{
		global $TCA, $LANG, $BACK_PATH, $BE_USER;

		$buttons = array(
			'csh' => '',
			'view' => '',
			'record_list' => '',
			'shortcut' => '',
		);
			// CSH
		$buttons['csh'] = t3lib_BEfunc::cshItem('_MOD_web_func', '', $GLOBALS['BACK_PATH'], '', TRUE);

		if($this->id && is_array($this->pageinfo)) {

				// View page
			$buttons['view'] = '<a href="#"
					onclick="' . htmlspecialchars(t3lib_BEfunc::viewOnClick($this->pageinfo['uid'], $BACK_PATH, t3lib_BEfunc::BEgetRootLine($this->pageinfo['uid']))) . '"
					title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.showPage', 1) . '
				">' .	t3lib_iconWorks::getSpriteIcon('actions-document-view') . '</a>';

				// Shortcut
			if ($BE_USER->mayMakeShortcut())	{
				$buttons['shortcut'] = $this->doc->makeShortcutIcon('id, edit_record, pointer, new_unique_uid, search_field, search_levels, showLimit', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']);
			}

				// If access to Web>List for user, then link to that module.
			$buttons['record_list'] = t3lib_BEfunc::getListViewLink(
				array(
					'id' => $this->pageinfo['uid'],
					'returnUrl' => t3lib_div::getIndpEnv('REQUEST_URI'),
				),
				$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.showList')
			);

		}
		return $buttons;
	}
	
	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	 *
	 * @return	[type]		...
	 */
	function main() {
		global $BE_USER,$LANG,$BACK_PATH;

		// Access check...
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

			// Template markers
		$markers = array(
			'CSH' => '',
			'FUNC_MENU' => '',
			'CONTENT' => ''
		);

		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $BACK_PATH;
		$this->doc->setModuleTemplate('templates/func.html');

		// **************************
		// Main
		// **************************

		
		if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id)) {

				// JavaScript
			$this->doc->JScode = $this->doc->wrapScriptTags('
				script_ended = 0;
				function jumpToUrl(URL)	{	//
					window.location.href = URL;
				}
			');
			$this->doc->postCode=$this->doc->wrapScriptTags('
				script_ended = 1;
				if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
			');
			
				// Setting up the context sensitive menu:
			$this->doc->getContextMenuCode();
			
			$this->doc->form='<form action="index.php" method="post"><input type="hidden" name="id" value="'.$this->id.'" />';

			$vContent = $this->doc->getVersionSelector($this->id,1);
			if ($vContent)	{
				$this->content.=$this->doc->section('',$vContent);
			}
			
			if ( $this->domainId>0 && $this->domainRecord ){ 
				$this->content.=$this->doc->header('preview of page ' . $this->id . ' on domain ' . $this->domainRecord['domainName'] . '[' . $this->domainRecord['uid'] . ']'  );
			}
			$this->content.=$this->doc->section('',$this->getModuleContent($domainId) );

				// Setting up the buttons and markers for docheader
			$docHeaderButtons = $this->getButtons();
			$markers['CSH'] = $docHeaderButtons['csh'];
			$markers['FUNC_MENU'] = t3lib_BEfunc::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function']);
			$markers['CONTENT'] = $this->content;
			
		} else {
			// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage($LANG->getLL('title'));
			$this->content.=$this->doc->header($LANG->getLL('title'));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
		
			// Build the <body> for the module
		$this->content = $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);
			// Renders the module page
		$this->content = $this->doc->render(
			$LANG->getLL('title'),
			$this->content
		);
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent() {
		echo $this->content;
	}

	/**
	 * Generates the module content
	 *
	 * @return	void
	 */
	function getModuleContent($domainId) {
		global $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $CLIENT, $TYPO3_CONF_VARS;

		
		// Access check...
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		$parts = parse_url(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
		$dName = t3lib_BEfunc::getDomainStartPage($parts['host'], $parts['path']) ?
			t3lib_BEfunc::firstDomainRecord(t3lib_BEfunc::BEgetRootLine($this->id)) :
			'';

		// preview selected Domain
		if ($this->domainId > 0 && $this->domainRecord ) {
			$dName = $this->domainRecord['domainName'];
		}

		// preview of mount pages
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$sys_page->init(FALSE);
		$mountPointInfo = $sys_page->getMountPointInfo($this->id);
		if ($mountPointInfo && $mountPointInfo['overlay']) {
			$this->id = $mountPointInfo['mount_pid'];
			$addCmd .= '&MP=' . $mountPointInfo['MPvar'];
		}

		$this->url.= ( $dName ? (t3lib_div::getIndpEnv('TYPO3_SSL') ? 'https://' : 'http://') . $dName : $BACK_PATH . '..') . '/index.php?id=' . $this->id . ($this->type ? '&type=' . $this->type : '');

		$content.= '<iframe src="' . $this->url . '"  />';
		
		return $content;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multidomain_publishing/mod1/index.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multidomain_publishing/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_multidomainpublishing_module1');
$SOBE->init();

// Include files?
foreach ($SOBE->include_once as $INC_FILE)
	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();
?>