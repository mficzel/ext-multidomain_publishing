<?php

// get the pagetype from the domain if no explicit type is given
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['multidomain_publishing'] = 'EXT:'.$_EXTKEY.'/Classes/Hooks/class.tx_multidomainpublishing_hooks.php:&tx_multidomainpublishing_hooks->getPagetypeFromDomain';

// filter pages which should not be displayed on the given domain
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all']['multidomain_publishing'] = 'EXT:'.$_EXTKEY.'/Classes/Hooks/class.tx_multidomainpublishing_hooks.php:&tx_multidomainpublishing_hooks->filterCurrentlyDisplayedPage';

// add a hook for the enable fields which adds clauses for the tables 
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_page.php']['addEnableColumns']['multidomain_publishing'] = 'EXT:'.$_EXTKEY.'/Classes/Hooks/class.tx_multidomainpublishing_hooks.php:&tx_multidomainpublishing_hooks->addDomainEnableFields';

// add a hook to filter pages from the menus 
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/tslib/class.tslib_menu.php']['filterMenuPages']['multidomain_publishing'] = 'EXT:'.$_EXTKEY.'/Classes/Hooks/class.tx_multidomainpublishing_hooks.php:tx_multidomainpublishing_hooks';

?>
