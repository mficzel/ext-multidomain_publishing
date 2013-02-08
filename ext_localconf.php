<?php

$typo3VersionAsInteger = 0;

// try all available classes to get the version int
if (class_exists('\TYPO3\CMS\Core\Utility\VersionNumberUtility')) {
	$typo3VersionAsInteger = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
} else if (class_exists('t3lib_utility_VersionNumber')) {
	$typo3VersionAsInteger = t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version);
} else if (class_exists('t3lib_div')) {
	$typo3VersionAsInteger = t3lib_div::int_from_ver(TYPO3_version);
}

if ($typo3VersionAsInteger >= 6000000) {
	// Code for 6.x
	// get the pagetype from the domain if no explicit type is given
	$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V6/class.tx_multidomainpublishing_tslibFeHooks.php:&tx_multidomainpublishing_tslibFeHooks->checkAlternativeIdMethodsPostProcHook';
	// filter pages which should not be displayed on the given domain
	$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V6/class.tx_multidomainpublishing_tslibFeHooks.php:&tx_multidomainpublishing_tslibFeHooks->contentPostProcAllHook';
	// add a hook for the enable fields which adds clauses for the tables
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_page.php']['addEnableColumns'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V6/class.tx_multidomainpublishing_t3libPageHooks.php:&tx_multidomainpublishing_t3libPageHooks->addEnableColumnsHook';
	// add a hook to filter pages from the menus
	$TYPO3_CONF_VARS['SC_OPTIONS']['cms/tslib/class.tslib_menu.php']['filterMenuPages'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V6/class.tx_multidomainpublishing_tslibMenuHooks.php:tx_multidomainpublishing_tslibMenuHooks';
	// add hook to show domain constraints in page_module
	$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V6/class.tx_multidomainpublishing_txCmsLayoutHooks.php:tx_multidomainpublishing_txCmsLayoutHooks';
} else {
	// Code up to 4.x
	// get the pagetype from the domain if no explicit type is given
	$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V4/class.tx_multidomainpublishing_tslibFeHooks.php:&tx_multidomainpublishing_tslibFeHooks->checkAlternativeIdMethodsPostProcHook';
	// filter pages which should not be displayed on the given domain
	$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V4/class.tx_multidomainpublishing_tslibFeHooks.php:&tx_multidomainpublishing_tslibFeHooks->contentPostProcAllHook';
	// add a hook for the enable fields which adds clauses for the tables
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_page.php']['addEnableColumns'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V4/class.tx_multidomainpublishing_t3libPageHooks.php:&tx_multidomainpublishing_t3libPageHooks->addEnableColumnsHook';
	// add a hook to filter pages from the menus
	$TYPO3_CONF_VARS['SC_OPTIONS']['cms/tslib/class.tslib_menu.php']['filterMenuPages'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V4/class.tx_multidomainpublishing_tslibMenuHooks.php:tx_multidomainpublishing_tslibMenuHooks';
	// add hook to show domain constraints in page_module
	$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][$_EXTKEY] = 'EXT:'.$_EXTKEY.'/Classes/Hooks_V4/class.tx_multidomainpublishing_txCmsLayoutHooks.php:tx_multidomainpublishing_txCmsLayoutHooks';
}
?>
