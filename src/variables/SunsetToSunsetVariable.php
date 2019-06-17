<?php
/**
 * Sunset To Sunset plugin for Craft CMS 3.x
 *
 * Keep the hours of the Sabbath holy.
 *
 * @link      https://cavellblood.com
 * @copyright Copyright (c) 2019 Cavell L. Blood
 */

namespace cavellblood\sunsettosunset\variables;

use cavellblood\sunsettosunset\SunsetToSunset;

use Craft;

/**
 * Sunset To Sunset Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.sunsetToSunset }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Cavell L. Blood
 * @package   SunsetToSunset
 * @since     2.0.0
 */
class SunsetToSunsetVariable
{
    // Public Methods
    // =========================================================================

    public function getPluginName()
    {
        return SunsetToSunset::$plugin->getPluginName();
    }

    public function getSettingsUrl()
    {
        return SunsetToSunset::$plugin->getSettingsUrl();
    }

    public function getPluginVersion()
    {
        return '2.0.0';
    }

    public function getPluginUrl()
    {
        return 'url here';
    }
}
