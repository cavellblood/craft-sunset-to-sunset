<?php
/**
 * Sunset To Sunset plugin for Craft CMS 3.x
 *
 * Keep the hours of the Sabbath holy.
 *
 * @link      https://cavellblood.com
 * @copyright Copyright (c) 2019 Cavell L. Blood
 */

namespace cavellblood\sunsettosunset;

use cavellblood\sunsettosunset\services\Base as BaseService;
use cavellblood\sunsettosunset\variables\SunsetToSunsetVariable;
use cavellblood\sunsettosunset\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Cavell L. Blood
 * @package   SunsetToSunset
 * @since     2.0.0
 *
 * @property  BaseService $base
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class SunsetToSunset extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * SunsetToSunset::$plugin
     *
     * @var SunsetToSunset
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * SunsetToSunset::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->_registerCpRoutes();
        $this->_registerVariables();

        $request = Craft::$app->request;

//        SunsetToSunset::$plugin->base->getLatitude();
//        SunsetToSunset::$plugin->getSettings()->latitude;

        $template = SunsetToSunset::$plugin->getSettings()->templateRedirect;
        $urlMatchTemplate = ($request->url === $template);

        $simulateTime = SunsetToSunset::$plugin->getSettings()->simulateTime;
        $duringWeek = false;
        $beforeSabbath = false;
        $duringSabbath = false;
        $afterSabbath = false;

        if ($simulateTime) {
            switch ($simulateTime) {
                case 'before':
                    $beforeSabbath = true;
                    break;
                case 'during':
                    $duringSabbath = true;
                    break;
                case 'after':
                    $afterSabbath = true;
                    break;
            }
        } else {
            $duringWeek = date('U') < SunsetToSunset::$plugin->base->getClosingTime();
            $beforeSabbath = date('U') < SunsetToSunset::$plugin->base->getClosingTime() && date('U') > SunsetToSunset::$plugin->base->getShowMessageTime();
            $duringSabbath = date('U') >= SunsetToSunset::$plugin->base->getClosingTime() && date('U') <= SunsetToSunset::$plugin->base->getOpeningTime() && date('w') >= SunsetToSunset::$plugin->base->getClosingDayNumber();
            $afterSabbath  = date('U') > SunsetToSunset::$plugin->base->getOpeningTime() && date('w') >= SunsetToSunset::$plugin->base->getOpeningDayNumber();
        }

        // Convert specific redirect urls to array
        $specificRedirectUrls = preg_split("/\r\n|\n|\r/", SunsetToSunset::$plugin->getSettings()->specificRedirectUrls);

        if ($request->isSiteRequest) {

            // Before Sabbath
            if ( $beforeSabbath )
            {
                if (SunsetToSunset::$plugin->getSettings()->showBannerOnSpecificUrls && count($specificRedirectUrls)) {
                    foreach ($specificRedirectUrls as $url) {
                        if (preg_match('('. $url . ')i', $request->url)) {
                            // Render Template
                            Craft::$app->view->hook('sunset-to-sunset-banner', function(array &$context) {
                                return SunsetToSunset::$plugin->base->renderBanner();
                            });
                        }
                    }
                } else {
                    // Render Template
                    Craft::$app->view->hook('sunset-to-sunset-banner', function(array &$context) {
                        return SunsetToSunset::$plugin->base->renderBanner();
                    });
                }
            }

            // During Sabbath and not on Sabbath URL
            if ( $duringSabbath && !$urlMatchTemplate )
            {
                if (count($specificRedirectUrls)) {
                    foreach ($specificRedirectUrls as $url) {
                        if (preg_match('('. $url . ')i', $request->url)) {
                            // Render Template
                            Craft::$app->view->hook('sunset-to-sunset-full-message', function(array &$context) {
                                return SunsetToSunset::$plugin->base->renderFullMessage();
                            });
                        }
                    }
                } else {
                    $request->redirect($template, true, 302);
                }
            }

            // During the week or after Sabbath
            if ( $duringWeek || $afterSabbath )
            {
                // If site is open and on message template redirect
                if ( $request->isSiteRequest && $urlMatchTemplate ) {
                    $request->redirect('/', true, 302);
                }
            }
        }

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'sunset-to-sunset',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function getPluginName()
    {
        return Craft::t('sunset-to-sunset', $this->getSettings()->pluginName);
    }

    public function getSettingsUrl()
    {
        return 'sunset-to-sunset';
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        // Get and pre-validate the settings
        $settings = $this->getSettings();
        $settings->validate();

        return Craft::$app->view->renderTemplate(
            'sunset-to-sunset/settings',
            [
                'settings' => $settings,
            ]
        );
    }

    // Private Methods
    // =========================================================================

    private function _registerCpRoutes()
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, [

                    'sunset-to-sunset' => 'sunset-to-sunset/base/settings',
                    'sunset-to-sunset/message' => 'sunset-to-sunset/base/settings',
                    'sunset-to-sunset/location' => 'sunset-to-sunset/base/settings-location',
                    'sunset-to-sunset/template' => 'sunset-to-sunset/base/settings-template',
                    'sunset-to-sunset/advanced' => 'sunset-to-sunset/base/settings-advanced',
                ]);
            }
        );
    }

    private function _registerVariables()
    {
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('sunsetToSunset', SunsetToSunsetVariable::class);
            }
        );
    }
}
