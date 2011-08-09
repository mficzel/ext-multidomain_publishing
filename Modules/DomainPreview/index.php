<?php
/***************************************************************
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
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


$LANG->includeLLFile('EXT:multidomain_publishing/Modules/DomainPreview/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]


/**
 * Module 'Domain Preview' for the 'multidomain_publishing' extension.
 *
 * @author	Thomas Hempel, Martin Ficzel <hempel@work.de, ficzel@work.de>
 * @package	TYPO3
 * @subpackage	tx_multidomainpublishing
 */
class  tx_multidomainpublishing_module1 extends t3lib_SCbase {
	
				var $MOD_MENU=array();

		
				var $perms_clause;
				var $modTSconfig;
				var $type;
				var $pageinfo;
				var $url;
				var $id;
				var $wsInstruction='';	

	
				/**
				 * Initializes the Module
				 * @return	void
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					parent::init();

					$this->MCONF = $GLOBALS['MCONF'];
					$this->id = intval(t3lib_div::_GP('id'));

					$this->perms_clause = $BE_USER->getPagePermsClause(1);

						// page/be_user TSconfig settings and blinding of menu-items
					$this->modTSconfig = t3lib_BEfunc::getModTSconfig($this->id,'mod.'.$this->MCONF['name']);
					$this->type = intval($this->modTSconfig['properties']['type']);

				}

				/**
				 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
				 *
				 * @return	void
				 */
				function menuConfig()	{
					
					$domainRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'hidden=0' );
													
					$this->MOD_MENU = Array (
						'function' => Array (
							/*

							'1' => $LANG->getLL('function1'),
							'2' => $LANG->getLL('function2'),
							'3' => $LANG->getLL('function3'),
							 * 
							 */
						)
					);
					
					debug ($domainRecords);
					
					foreach ($domainRecords as $domainRecord){
						$this->MOD_MENU['function'][ $domainRecord['uid'] ] =  $domainRecord['domainName'] ;
					}
					parent::menuConfig();
				}

				/**
				 * Main function of the module. Write the content to $this->content
				 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
				 *
				 * @return	[type]		...
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					// Access check!
					// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;
				
					if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{

							// Draw the header.
						$this->doc = t3lib_div::makeInstance('mediumDoc');
						$this->doc->backPath = $BACK_PATH;
						$this->doc->form='<form action="" method="post" enctype="multipart/form-data">';

							// JavaScript
						$this->doc->JScode = '
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						';
						$this->doc->postCode='
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';

						$headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']) . '<br />'
							. $LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.path') . ': ' . t3lib_div::fixed_lgd_cs($this->pageinfo['_thePath'], -50);

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
						$this->content.=$this->doc->divider(5);


						// Render content:
						$this->moduleContent();


						// ShortCut
						if ($BE_USER->mayMakeShortcut())	{
							$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
						}

						$this->content.=$this->doc->spacer(10);
					} else {
							// If no access or if ID == zero

						$this->doc = t3lib_div::makeInstance('mediumDoc');
						$this->doc->backPath = $BACK_PATH;

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->spacer(10);
					}
				
				}

				/**
				 * Prints out the module HTML
				 *
				 * @return	void
				 */
				function printContent()	{

					$this->content.=$this->doc->endPage();
					echo $this->content;
				}

				/**
				 * Generates the module content
				 *
				 * @return	void
				 */
				function moduleContent(){
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					$domainId = (int)$this->MOD_SETTINGS['function'];
					$this->content.= 'id: '.$domainId;
					
						// Access check...
						// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;
					$addCmd='';
					if ($this->id && $access)	{
						$addCmd = '&ADMCMD_view=1&ADMCMD_editIcons=1'.t3lib_BEfunc::ADMCMD_previewCmds($this->pageinfo);
					}

					$parts = parse_url(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
					$dName = t3lib_BEfunc::getDomainStartPage($parts['host'],$parts['path']) ?
									t3lib_BEfunc::firstDomainRecord(t3lib_BEfunc::BEgetRootLine($this->id)):
									'';
					
						// preview selected Domain
					if ($domainId > 0){
						$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_domain', 'uid=' . $domainId . ' AND hidden=0' );
						
						$this->content.= 'preview: '.$domainRecord['domainName'];
							
						$dName = $domainRecord['domainName'];
					}

						// preview of mount pages
					$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
					$sys_page->init(FALSE);
					$mountPointInfo = $sys_page->getMountPointInfo($this->id);
					if ($mountPointInfo && $mountPointInfo['overlay']) {
						$this->id = $mountPointInfo['mount_pid'];
						$addCmd .= '&MP=' . $mountPointInfo['MPvar'];
					}

					$this->url.= ($dName?(t3lib_div::getIndpEnv('TYPO3_SSL') ? 'https://' : 'http://').$dName:$BACK_PATH.'..').'/index.php?id='.$this->id.($this->type?'&type='.$this->type:'').$addCmd;
					
					$this->content.= '<iframe src="'.$this->url.'" width="300" height="300" />';
						
				}
				
		}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multidomain_publishing/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multidomain_publishing/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_multidomainpublishing_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>