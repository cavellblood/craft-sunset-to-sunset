<?php
/**
 * Sunset to Sunset plugin for Craft CMS 3.x
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
 * Sunset to Sunset Variable
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

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.sunsetToSunset.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.sunsetToSunset.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
