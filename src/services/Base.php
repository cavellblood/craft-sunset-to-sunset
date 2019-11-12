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
use cavellblood\sunsettosunset\assetbundles\duringsabbath\DuringSabbathAsset;

use Craft;
use craft\base\Component;
use craft\web\View;

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
     * @return int
     */
    public function getShowMessageTime()
    {
        $time = SunsetToSunset::$plugin->getSettings()->showMessageTime;

        // Set opening time
        $result = $this->getClosingTime() - ( $time * 60 );

        return (int)$result;
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
        date_default_timezone_set( $this->getSetting('timezone') );

        // Get opening date and time information
        $daysToOpening     = $this->getOpeningDayNumber() - date('w');
        $openingDay        = strtotime( date( 'Y-m-d' ) . '+ '. $daysToOpening .' days');
        $openingDaySunInfo = date_sun_info( $openingDay, $this->getSetting('latitude'), $this->getSetting('longitude') );

        // Set opening time
        $result = (int)$openingDaySunInfo['sunset'] + ( $this->getSetting('guard') * 60 );

        return $result;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function renderBanner()
    {

        $vars = array(
            'bannerCssPosition' => SunsetToSunset::$plugin->getSettings()->bannerCssPosition,
            'bannerCssBackgroundColor' => SunsetToSunset::$plugin->getSettings()->bannerCssBackgroundColor,
            'bannerMessage' => SunsetToSunset::$plugin->getSettings()->bannerMessage,
            'openingTime' => SunsetToSunset::$plugin->base->getOpeningTime(),
            'closingTime' => SunsetToSunset::$plugin->base->getClosingTime()
        );
        
        $originalTemplateMode = Craft::$app->getView()->getTemplateMode();
        Craft::$app->getView()->setTemplateMode(View::TEMPLATE_MODE_CP);

        $html = Craft::$app->getView()->renderTemplate('sunset-to-sunset/frontend/banner', $vars);

        Craft::$app->getView()->setTemplateMode($originalTemplateMode);

        return $html;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function renderFullMessage()
    {
        $vars = array(
            'message' => SunsetToSunset::$plugin->getSettings()->message,
            'openingTime' => SunsetToSunset::$plugin->base->getOpeningTime(),
            'closingTime' => SunsetToSunset::$plugin->base->getClosingTime()
        );

        if (SunsetToSunset::$plugin->getSettings()->messageTemplate !== '') {
            SunsetToSunset::$plugin->view->registerAssetBundle(DuringSabbathAsset::class);
            $html = '<div class="sts-full-message__container">';
            $html .= Craft::$app->getView()->renderTemplate(SunsetToSunset::$plugin->getSettings()->messageTemplate, $vars);
            $html .= '</div>';
        } else {
            $originalTemplateMode = Craft::$app->getView()->getTemplateMode();
            Craft::$app->getView()->setTemplateMode(View::TEMPLATE_MODE_CP);
    
            $html = Craft::$app->getView()->renderTemplate('sunset-to-sunset/frontend/fullmessage', $vars);
    
            Craft::$app->getView()->setTemplateMode($originalTemplateMode);
        }

        return $html;
    }
}
