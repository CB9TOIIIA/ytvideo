<?php
/*
 * @package     Joomla.Package
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\CMS\Language\Text;

class pkg_ytvideoInstallerScript
{
    function preflight($type, $parent)
    {
        if (strtolower($type) === 'uninstall') {
            return true;
        }

		$minJoomlaVersion = $parent->getManifest()->attributes()->version[0];

		if (!class_exists('Joomla\CMS\Version')) {
			JFactory::getApplication()->enqueueMessage(JText::sprintf('J_JOOMLA_COMPATIBLE', JText::_($parent->getName()), $minJoomlaVersion), 'error');
			return false;
        }

        $msg = '';
        $ver = new Version();
        $name = Text::_($parent->getName());
        $minPhpVersion = $parent->getManifest()->php_minimum[0];

        if (version_compare($ver->getShortVersion(), $minJoomlaVersion, 'lt')) {
            $msg .= Text::sprintf('PKG_SNIPPET_JOOMLA_COMPATIBLE', $name, $minJoomlaVersion);
        }

        if (version_compare(phpversion(), $minPhpVersion, 'lt')) {
            $msg .= Text::sprintf('PKG_SNIPPET_PHP_COMPATIBLE', $name, $minPhpVersion);
        }

        if ($msg) {
            Factory::getApplication()->enqueueMessage($msg, 'error');
            return false;
        }
    }
}
