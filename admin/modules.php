<?php
/**
* XOOPS - PHP Content Management System
* Copyright (c) 2001 - 2006 <http://www.xoops.org/>
*
* Module: xoopsinfo 2.0
* Licence : GPL
* Authors :
*              - Jmorris
*              - Marco
*              - Christian
*              - DuGris (http://www.dugris.info)
*/
include '../../../include/cp_header.php';
if (!defined('XOOPS_ROOT_PATH')) { die('XOOPS root path not defined'); }
define('XOOPSINFO_URL',XOOPS_URL . '/modules/printliminator/');
define('XOOPSINFO_URL_IMAGE',XOOPS_URL . '/modules/printliminator/assets/images/icons');
define('XOOPSINFO_ADMIN_URL',XOOPS_URL . '/modules/printliminator/admin');
define('XOOPSINFO_PATH',XOOPS_ROOT_PATH . '/modules/printliminator/');

global $xoopsDB, $xoopsConfig, $xoopsModule;

include_once( XOOPS_ROOT_PATH.'/class/xoopsformloader.php' );
include_once( XOOPS_ROOT_PATH . '/modules/printliminator/include/functions_xi.php');
@include_once( XOOPS_ROOT_PATH.'/modules/printliminator/xoops_version.php' );

if ( file_exists( XOOPS_ROOT_PATH . '/modules/printliminator/language/' . $xoopsConfig['language'] . '/modinfo.php' ) ) {
	include_once(XOOPS_ROOT_PATH . '/modules/printliminator/language/' . $xoopsConfig['language'] . '/modinfo.php');
} else {
	include_once(XOOPS_ROOT_PATH . '/modules/printliminator/language/english/modinfo.php');
}

if ( file_exists( XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/global.php' ) ) {
	@include_once( XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/global.php');
} else {
	@include_once( XOOPS_ROOT_PATH . '/language/english/admin/global.php');
}

if ( file_exists( XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/global.php' ) ) {
	@include_once( XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/global.php');
} else {
	@include_once( XOOPS_ROOT_PATH . '/language/english/admin/global.php');
}

if ( file_exists( XOOPS_ROOT_PATH . '/modules/system/language/' . $xoopsConfig['language'] . '/admin.php' ) ) {
	@include_once( XOOPS_ROOT_PATH . '/modules/system/language/' . $xoopsConfig['language'] . '/admin.php');
} else {
	@include_once( XOOPS_ROOT_PATH . '/modules/system/language/english/admin/admin.php');
}

if ( file_exists( XOOPS_ROOT_PATH . '/modules/system/language/' . $xoopsConfig['language'] . '/admin/preferences.php' ) ) {
	@include_once( XOOPS_ROOT_PATH . '/modules/system/language/' . $xoopsConfig['language'] . '/admin/preferences.php');
} else {
	@include_once( XOOPS_ROOT_PATH . '/modules/system/language/english/admin/preferences.php');
}

$myts = MyTextSanitizer::getInstance();

$module_handler = xoops_gethandler('module');
$installed_mods = $module_handler->getObjects();

echo '<table width="100%">';
echo '<tr>';
echo "<td colspan='6' class='bold shadowlight alignmiddle' style='text-align:center;'><h2>" . _AM_XI_ADMENU4 . "</h2></td>";
echo '</tr>';

echo '<tr>';
echo '<th rowspan="2" style="text-align:center;vertical-align:middle;">' . _AM_XI_MODULE_NAME . '</th>';
echo '<th rowspan="2" style="text-align:center;vertical-align:middle;width:60px;">' . _AM_XI_MODULE_ACTION . '</th>';
echo '<th rowspan="2" style="text-align:center;vertical-align:middle;width:80px;">' . _AM_XI_MODULE_SUPPORT . '</th>';
echo '<th colspan="3" style="text-align:center;vertical-align:middle;">' . _AM_XI_MODULE_VERSION . '</th>';
echo '</tr>';
echo '<tr>';
echo '<th style="text-align:center;vertical-align:middle;width:60px;">' . _AM_XI_MODULE_XOOPS . '</th>';
echo '<th style="text-align:center;vertical-align:middle;width:60px;">' . _AM_XI_MODULE_TABLE . '</th>';
echo '<th style="text-align:center;vertical-align:middle;width:60px;">' . _AM_XI_MODULE_NEW . '</th>';
echo '</tr>';

foreach ( $installed_mods as $module ) {
	unset($modversion);
	@include( XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/xoops_version.php');

	$action = 0;
	$new_version= false;
	if ( !array_key_exists('status_fileinfo', $modversion) ) {
		$file = XOOPS_ROOT_PATH . '/modules/printliminator/plugins/modules/' . $module->getVar('dirname') . '.php';
		if ( file_exists( $file) ) {
			include_once( $file );
			if ($modversion['status_fileinfo']) {
				$new_version = @file_get_contents( $modversion['status_fileinfo'] );
			}
		}
	} else {
		if ($modversion['status_fileinfo']) {
			$new_version = @file_get_contents( $modversion['status_fileinfo'] );
		}
	}

	if ( $modversion['version'] != ($module->getVar('version')/100) ) {
		$action = 1;
		$class = '';
		$style = ' style="background : #ffa9a9;"';
	} elseif ( $modversion['version'] == ($module->getVar('version')/100) && ($modversion['version'] < $new_version && $new_version) ) {
		$action = 2;
		$class = '';
		$style = ' style="background : #b5d1fe;"';
	} elseif ( $modversion['version'] == ($module->getVar('version')/100) && ($modversion['version'] == $new_version && $new_version) ) {
		$action = 3;
		$class = 'odd';
		$style = '';
	} else {
		$action = 0;
		$class = 'odd';
		$style = '';
	}

	echo '<tr>';
	echo '<td class="' . $class . '"' . $style . '>';
	if ( array_key_exists('developer_website_url', $modversion) && $modversion['developer_website_url'] != '' ) {
		echo '<a target="_new" href="' . $modversion['developer_website_url'] . '">';
	}
	echo $module->getVar('name', 'E');
	if ( array_key_exists('developer_website_url', $modversion) && $modversion['developer_website_url'] != '' ) {
		echo '</a>';
	}
	echo '</td>';

	echo '<td class="' . $class . '"' . $style . ' align="center">';

	echo '<a href="' .XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $module->getVar('dirname') . '">
			<img src="' . XOOPSINFO_URL_IMAGE . '/update.gif" alt="' . _AM_XI_MODULE_UPDATE . '"align="absmiddle" />
			</a>&nbsp;';

	if ( $action == 2 || $action == 0) {
		if ( array_key_exists( 'download_website', $modversion ) && $modversion['download_website']!= '' ) {
			echo '<a target="_blank" href="' . $modversion['download_website'] . '">';
			echo '<img src="' . XOOPSINFO_URL_IMAGE . '/download.gif" alt="' . _AM_XI_MODULE_DOWNLOAD . '"align="absmiddle" />';
			echo '</a>';
		} else {
			echo '<img src="' . XOOPSINFO_URL_IMAGE . '/blank.png" alt="" width="22" height="22" />';
		}
	} else {
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/blank.png" alt="" width="22" height="22" />';
	}
	echo '</td>';

	echo '<td class="' . $class . '"' . $style . ' align="center">';
	if ( array_key_exists('support_site_url', $modversion) && $modversion['support_site_url'] != '' ) {
		echo '<a target="_blank" href="' . $modversion['support_site_url'] . '">';
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/forum.gif" alt="' . _AM_XI_MODULE_FORUM . '"/>';
		echo '</a>&nbsp;';
	} else {
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/blank.png" alt="" width="20" height="20"/>&nbsp;';
	}
	if ( array_key_exists('submit_feature', $modversion) && $modversion['submit_feature'] != '' ) {
		echo '<a target="_blank" href="' . $modversion['submit_feature'] . '">';
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/feature.gif" alt="' . _AM_XI_MODULE_FEATURE . '"/>';
		echo '</a>&nbsp;';
	} else {
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/blank.png" alt="" width="20" height="20"/>&nbsp;';
	}
	if ( array_key_exists('submit_bug', $modversion) && $modversion['submit_bug'] != '' ) {
		echo '<a target="_blank" href="' . $modversion['submit_bug'] . '">';
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/bugs.gif" alt="' . _AM_XI_MODULE_BUG . '"/>';
		echo '</a>';
	} else {
		echo '<img src="' . XOOPSINFO_URL_IMAGE . '/blank.png" alt="" width="20" height="20"/>';
	}
	echo '</td>';

	echo '<td class="' . $class . '"' . $style . ' align="center">';
	if ($action == 1) {
		echo '<b>';
	}

	echo number_format($modversion['version'],2);
	if ($action == 1) {
		echo '</b>';
	}
	echo '</td>';

	echo '<td class="' . $class . '"' . $style . ' align="center">';
	if ($action == 1) {
		echo '<b>';
	}
	echo number_format($module->getVar('version') / 100,2);
	if ($action == 1) {
		echo '</b>';
	}
	echo '</td>';

	echo '<td class="' . $class . '"' . $style . ' align="center">';
	if ($new_version) {
		if ($action == 2) {
			echo '<b>';
		}
		echo number_format($new_version,2);
		if ($action == 2) {
			echo '</b>';
		}
	}
	echo '</td>';

	echo '</tr>';
}
echo '</table>';
echo '<br />';

echo '<table width="70%" align="center">';
echo '<tr>';
echo '<td style="width:10%;background : #ffa9a9;">&nbsp;</td>';

echo '<td><b>';
echo _AM_XI_MODULE_LEGEND_UPDATE;
echo '</b></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="width:10%;background : #b5d1fe;">&nbsp;</td>';

echo '<td><b>';
echo _AM_XI_MODULE_LEGEND_DOWNLOAD;
echo '</b></td>';
echo '</tr>';
echo '</table><br /><br />';
