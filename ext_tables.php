<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
	'tx_multidomainpublishing_visibility' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:pages.tx_multidomainpublishing_visibility',		
		'config' => array (
			'type' => 'select',	
			'foreign_table' => 'sys_domain',
			//'itemsProcFunc' => @todo remove domains not in current tree
			'size' => 5,	
			'minitems' => 0,
			'maxitems' => 99,
			'foreign_table_loadIcons' => 1,
		)
	),
);

t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages','tx_multidomainpublishing_visibility;;;;1-1-1', '', 'after:fe_group');

$tempColumns = array (
	'tx_multidomainpublishing_visibility' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:multidomain_publishing/locallang_db.xml:tt_content.tx_multidomainpublishing_visibility',		
		'config' => array (
			'type' => 'select',	
			'foreign_table' => 'sys_domain',	
			//'itemsProcFunc' => @todo remove domains not in current tree
			'size' => 5,
			'minitems' => 0,
			'maxitems' => 99,
			'foreign_table_loadIcons' => 1,
		)
	),
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_multidomainpublishing_visibility;;;;1-1-1' , '', 'after:fe_group');


if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModulePath('txmultidomainpublishingModuleDomainPreview', t3lib_extMgm::extPath($_EXTKEY) . 'Modules/DomainPreview/');	
	t3lib_extMgm::addModule('web', 'txmultidomainpublishingModuleDomainPreview', 'after:layout', t3lib_extMgm::extPath($_EXTKEY) . 'Modules/DomainPreview/');
}
?>