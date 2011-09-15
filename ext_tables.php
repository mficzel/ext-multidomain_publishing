<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumnsPages = array (
	'tx_multidomainpublishing_visibility' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:pages.tx_multidomainpublishing_visibility',		
		'config' => array (
			'type' => 'select',	
			'foreign_table' => 'sys_domain',
			'itemsProcFunc' => 'EXT:'.$_EXTKEY.'/Classes/Helpers/class.tx_multidomainpublishing_tcaProcHelper.php:&tx_multidomainpublishing_tcaProcHelper->selectDomainRestrictionsProcFunction',
			'size' => 5,	
			'minitems' => 0,
			'maxitems' => 99,
			'foreign_table_loadIcons' => 1,
		)
	),
);

t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumnsPages,1);
t3lib_extMgm::addToAllTCAtypes('pages','tx_multidomainpublishing_visibility;;;;1-1-1', '', 'after:fe_group');
$GLOBALS['TCA']['pages']['ctrl']['tx_multidomainpublishing_column'] = 'tx_multidomainpublishing_visibility';

$tempColumnsContent = array (
	'tx_multidomainpublishing_visibility' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:tt_content.tx_multidomainpublishing_visibility',		
		'config' => array (
			'type' => 'select',	
			'foreign_table' => 'sys_domain',	
			'itemsProcFunc' => 'EXT:'.$_EXTKEY.'/Classes/Helpers/class.tx_multidomainpublishing_tcaProcHelper.php:&tx_multidomainpublishing_tcaProcHelper->selectDomainRestrictionsProcFunction',
			'size' => 5,
			'minitems' => 0,
			'maxitems' => 99,
			'foreign_table_loadIcons' => 1,
		)
	),
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumnsContent,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_multidomainpublishing_visibility;;;;1-1-1' , '', 'after:fe_group');
$GLOBALS['TCA']['tt_content']['ctrl']['tx_multidomainpublishing_column'] = 'tx_multidomainpublishing_visibility';

$tempColumnsSysDomain = array (
	'tx_multidomainpublishing_pagetype' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:sys_domain.tx_multidomainpublishing_pagetype',		
		'config' => array (
			'type' => 'input',	
			'size' => 10,
			'eval' => 'int',
			'default' => '0',
		)
	),
	'tx_multidomainpublishing_mode' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:sys_domain.tx_multidomainpublishing_mode',		
		'config' => array (
			'type' => 'select',	
			'items' => array(
				array('LLL:EXT:multidomain_publishing/locallang_db.xml:sys_domain.tx_multidomainpublishing_mode_deny', 0),
				array('LLL:EXT:multidomain_publishing/locallang_db.xml:sys_domain.tx_multidomainpublishing_mode_allow', 1),
			),
			'default' => '0'
		)
	),
);

t3lib_div::loadTCA('sys_domain');
t3lib_extMgm::addTCAcolumns('sys_domain',$tempColumnsSysDomain,1);
t3lib_extMgm::addToAllTCAtypes('sys_domain','tx_multidomainpublishing_pagetype;;;;1-1-1,tx_multidomainpublishing_mode');


// register Backend Module
if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModule('web', 'domainpreview', 'after:view', t3lib_extMgm::extPath($_EXTKEY) . 'Modules/DomainPreview/');
}


?>