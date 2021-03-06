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
	var $rootline;

	var $domainRecords;
	var $activeDomainId;
	var $activeDomainRecord;

	/**
	 * Document Template Object
	 *
	 * @var mediumDoc
	 */
	var $doc;

	function init() {

		// init ti get the id
		parent::init();

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$this->rootline = t3lib_BEfunc::BEgetRootLine($this->id);

		if ($this->rootline) {
			$rootlineSubConstraint = array();
			foreach ($this->rootline as $rootlineItem) {
				$rootlineSubConstraint[] = 'pid=' . intval($rootlineItem['uid']);
			}
			$rootlineConstraint = '( ' . implode(' OR ', $rootlineSubConstraint) . ' )';
		} else {
			$rootlineConstraint = '1';
		}

		$this->domainRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'hidden=0 AND ' . $rootlineConstraint );

		$this->MOD_MENU = Array(
			'function' => Array()
		);

		foreach ($this->domainRecords as $domainRecord) {
			$this->MOD_MENU['function'][$domainRecord['uid']] = $domainRecord['domainName'];
		}

		// init again to check parameter against currently modified MOD_MENU
		parent::init();

		$this->activeDomainId = (int) $this->MOD_SETTINGS['function'];
		if ($this->activeDomainId > 0) {
			$this->activeDomainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'uid=' . $this->activeDomainId . ' AND hidden=0');
		}
	}

	/**
	 * Create the panel of buttons for submitting the form or otherwise perform operations.
	 *
	 * @return	array	all available buttons as an assoc. array
	 */
	protected function getButtons() {
		global $BACK_PATH, $BE_USER;

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
			if ($BE_USER->mayMakeShortcut()) {
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
		$access = is_array($this->pageinfo) ? 1 : 0;

			// Template markers
		$markers = array(
			'CSH' => '',
			'FUNC_MENU' => '',
			'CONTENT' => ''
		);

		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->setModuleTemplate('template.html');

		// **************************
		// Main
		// **************************

		if ($this->id && $access) {

			$content = '';

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
			if ($vContent) {
				$content .= $this->doc->section('',$vContent);
			}

			if ($this->activeDomainId > 0 && $this->activeDomainRecord) {
				$content .= $this->doc->header(sprintf($LANG->getLL('previewPageOnDomain'), $this->pageinfo['title'], $this->activeDomainRecord['domainName']));
			}
			$url = $this->getUrlForDomain($this->activeDomainId);

			$funcMenu = t3lib_BEfunc::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function']);
			$popupLink =  $this->getPopupLinkForUrl($url);
			$iframePreview = $this->getIframeForUrl($url);

			$content .= $this->doc->section('Domain', $funcMenu . $popupLink );
			$content .= $this->doc->section('Preview', $iframePreview);

				// Setting up the buttons and markers for docheader
			$docHeaderButtons = $this->getButtons();
			$markers['CSH'] = $docHeaderButtons['csh'];
			$markers['FUNC_MENU'] = '';
			$markers['CONTENT'] = $content;

		} else {

				// If no access or if ID == zero
			$flashMessage = t3lib_div::makeInstance(
				't3lib_FlashMessage',
				$LANG->getLL('clickAPage_content'),
				$LANG->getLL('title'),
				t3lib_FlashMessage::INFO
			);

				// Setting up the buttons and markers for docheader
			$docHeaderButtons = $this->getButtons();
			$markers['CSH'] = $docHeaderButtons['csh'];
			$markers['CONTENT'] = $flashMessage->render();;
		}

			// Build the <body> for the module
		$bodyContent = $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);

			// Renders the module page
		$this->content = $this->doc->render(
			$LANG->getLL('title'),
			$bodyContent
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
	function getUrlForDomain($domainId) {
		global $BACK_PATH;

		// Access check...
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		$parts = parse_url(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
		$dName = t3lib_BEfunc::getDomainStartPage($parts['host'], $parts['path']) ?
			t3lib_BEfunc::firstDomainRecord(t3lib_BEfunc::BEgetRootLine($this->id)) :
			'';

		// preview selected Domain
		if ($this->activeDomainId > 0 && $this->activeDomainRecord ) {
			$dName = $this->activeDomainRecord['domainName'];
		}

		// preview of mount pages
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$sys_page->init(FALSE);

		$url = ( $dName ? (t3lib_div::getIndpEnv('TYPO3_SSL') ? 'https://' : 'http://') . $dName : $BACK_PATH . '..') . '/index.php?id=' . $this->id . ($this->type ? '&type=' . $this->type : '');
		return $url;
	}

	function getIframeForUrl($url) {
		return '<iframe src="' . $url . '"  />';
	}

	function getPopupLinkForUrl($url) {
		return '<a href="#" onclick="var previewWin = window.open(\'' . $url . '\',\'newTYPO3frontendWindow\');previewWin.focus();" title="Show page"> open - ' . $url . '</span></a>';
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