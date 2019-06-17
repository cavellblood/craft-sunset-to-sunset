<?php
/**
 * Sunset To Sunset plugin for Craft CMS 3.x
 *
 * Keep the hours of the Sabbath holy.
 *
 * @link      https://cavellblood.com
 * @copyright Copyright (c) 2019 Cavell L. Blood
 */

namespace cavellblood\sunsettosunset\services;

use cavellblood\sunsettosunset\SunsetToSunset;

use Craft;
use craft\base\Component;

/**
 * Base Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Cavell L. Blood
 * @package   SunsetToSunset
 * @since     2.0.0
 */
class Base extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     SunsetToSunset::$plugin->base->exampleService()
     *
     * @return mixed
     */

    public function getSetting($setting)
    {
        $result = SunsetToSunset::$plugin->getSettings()->$setting;

        return $result;
    }

    /**
     * @return int
     */
    public function getClosingDayNumber()
    {
        // Set day of week: zero-based index
        return 5;
    }

    /**
     * @return int
     */
    public function getOpeningDayNumber()
    {
        // Set day of week: zero-based index
        return 6;
    }

    /**
     * @return float|int
     */
    public function getClosingTime()
    {
        // Set default time zone for date_sun_info to work with
        date_default_timezone_set( $this->getSetting('timezone') );

        // Get closing date and time information
        $daysToClosing     = $this->getClosingDayNumber() - date('w');
        $closingDay        = strtotime( date( 'Y-m-d' ) . '+ '. $daysToClosing .' days');
        $closingDaySunInfo = date_sun_info( $closingDay, $this->getSetting('latitude'), $this->getSetting('longitude') );

        // Set closing time
        $result = (int)$closingDaySunInfo['sunset'] - ( $this->getSetting('guard') * 60 );

        return $result;
    }

    /**
     * @return float|int
     */
    public function getOpeningTime()
    {
        // Set default time zone for date_sun_info to work with
        date_default_timezone_set( $this->getTimeZone() );

        // Get opening date and time information
        $daysToOpening     = $this->getOpeningDayNumber() - date('w');
        $openingDay        = strtotime( date( 'Y-m-d' ) . '+ '. $daysToOpening .' days');
        $openingDaySunInfo = date_sun_info( $openingDay, $this->getLatitude(), $this->getLongitude() );

        // Set opening time
        $result = (int)$openingDaySunInfo['sunset'] + ( $this->getGuard() * 60 );

        return $result;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $oldTemplatesPath = craft()->path->getTemplatesPath();
        $newTemplatesPath = craft()->path->getPluginsPath().'sunsettosunset/templates/';
        craft()->path->setTemplatesPath($newTemplatesPath);

        $vars = array(
            'bannerMessage' => craft()->sunsetToSunset->getBannerMessage(),
            'openingTime' => craft()->sunsetToSunset->getOpeningTime(),
            'closingTime' => craft()->sunsetToSunset->getClosingTime()
        );

        $html = craft()->templates->render('frontend/message', $vars);
        craft()->path->setTemplatesPath($oldTemplatesPath);

        return $html;
    }
}
