<?php
/**
 * Sunset To Sunset plugin for Craft CMS 3.x
 *
 * Keep the hours of the Sabbath holy.
 *
 * @link      https://cavellblood.com
 * @copyright Copyright (c) 2019 Cavell L. Blood
 */

namespace cavellblood\sunsettosunset\controllers;

use cavellblood\sunsettosunset\SunsetToSunset;

use Craft;
use craft\web\Controller;

/**
 * Base Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Cavell L. Blood
 * @package   SunsetToSunset
 * @since     2.0.0
 */
class BaseController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/sunset-to-sunset/base
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the BaseController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/sunset-to-sunset/base/do-something
     *
     * @return mixed
     */
    public function actionSettings()
    {
        $settings = SunsetToSunset::$plugin->getSettings();

        return $this->renderTemplate('sunset-to-sunset/settings/message', [
            'settings' => $settings,
        ]);
    }

    public function actionSettingsLocation()
    {
        $settings = SunsetToSunset::$plugin->getSettings();

        return $this->renderTemplate('sunset-to-sunset/settings/location', [
            'settings' => $settings,
        ]);
    }

    public function actionSettingsTemplate()
    {
        $settings = SunsetToSunset::$plugin->getSettings();

        return $this->renderTemplate('sunset-to-sunset/settings/template', [
            'settings' => $settings,
        ]);
    }

    public function actionSettingsAdvanced()
    {
        $settings = SunsetToSunset::$plugin->getSettings();

        return $this->renderTemplate('sunset-to-sunset/settings/advanced', [
            'settings' => $settings,
        ]);
    }
}
