<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Alexweb.' . $_EXTKEY,
	'Feawweather',
	array(
		'Weather' => 'list',
		
	),
	// non-cacheable actions
	array(
		'Weather' => '',
		
	)
);

?>