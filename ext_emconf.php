<?php

########################################################################
# Extension Manager/Repository config file for ext "multidomain_publishing".
#
# Auto generated 04-10-2011 13:16
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Multidomain Publishing',
	'description' => 'Manage content for multiple domains in a single pagetree.',
	'category' => 'fe',
	'author' => 'Thomas Hempel, Martin Ficzel',
	'author_email' => 'hempel@work.de, ficzel@work.de',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.0.1',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.5.0-0.0.0',
			'php' => '5.2.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:31:{s:9:"ChangeLog";s:4:"8c0a";s:10:"README.txt";s:4:"ee2d";s:16:"ext_autoload.php";s:4:"b72d";s:12:"ext_icon.gif";s:4:"170b";s:12:"ext_icon.png";s:4:"545a";s:12:"ext_icon.pxm";s:4:"b548";s:17:"ext_localconf.php";s:4:"2e5f";s:14:"ext_tables.php";s:4:"a8dd";s:14:"ext_tables.sql";s:4:"85d3";s:16:"locallang_db.xml";s:4:"d6fd";s:52:"Classes/class.tx_multidomainpublishing_constants.php";s:4:"b504";s:63:"Classes/Helpers/class.tx_multidomainpublishing_domainHelper.php";s:4:"fd58";s:64:"Classes/Helpers/class.tx_multidomainpublishing_tcaProcHelper.php";s:4:"cdff";s:63:"Classes/Hooks/class.tx_multidomainpublishing_t3libPageHooks.php";s:4:"829f";s:61:"Classes/Hooks/class.tx_multidomainpublishing_tslibFeHooks.php";s:4:"a500";s:63:"Classes/Hooks/class.tx_multidomainpublishing_tslibMenuHooks.php";s:4:"09d7";s:65:"Classes/Hooks/class.tx_multidomainpublishing_txCmsLayoutHooks.php";s:4:"5126";s:30:"Modules/DomainPreview/conf.php";s:4:"3fba";s:31:"Modules/DomainPreview/index.php";s:4:"b3c2";s:35:"Modules/DomainPreview/locallang.xml";s:4:"d989";s:39:"Modules/DomainPreview/locallang_mod.xml";s:4:"c89b";s:36:"Modules/DomainPreview/moduleicon.gif";s:4:"8074";s:36:"Modules/DomainPreview/moduleicon.png";s:4:"5e81";s:36:"Modules/DomainPreview/moduleicon.pxm";s:4:"25c3";s:35:"Modules/DomainPreview/template.html";s:4:"8390";s:14:"doc/manual.odt";s:4:"bdeb";s:14:"doc/manual.pdf";s:4:"90e4";s:14:"doc/manual.sxw";s:4:"afc2";s:14:"doc/manual.txt";s:4:"e67a";s:19:"doc/wizard_form.dat";s:4:"759a";s:20:"doc/wizard_form.html";s:4:"302d";}',
	'suggests' => array(
	),
);

?>