<?php
/**
 * Sunset To Sunset plugin for Craft CMS 3.x
 *
 * Keep the hours of the Sabbath holy.
 *
 * @link      https://cavellblood.com
 * @copyright Copyright (c) 2019 Cavell L. Blood
 */

namespace cavellblood\sunsettosunset\models;

use cavellblood\sunsettosunset\SunsetToSunset;

use Craft;
use craft\base\Model;

/**
 * SunsetToSunset Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Cavell L. Blood
 * @package   SunsetToSunset
 * @since     2.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $pluginName = 'Sunset to Sunset';
    public $latitude;
    public $longitude;
    public $timezone;
    public $guard;
    public $message;
    public $bannerMessage;
    public $showMessageTime;
    public $showOnSpecificUrls;
    public $bannerCssPosition;
    public $bannerCssBackgroundColor;
    public $simulateTime;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->latitude === null) {
            $this->latitude = \Craft::t('sunset-to-sunset', '41.8333925');
        }

        if ($this->longitude === null) {
            $this->longitude = \Craft::t('sunset-to-sunset', '-88.0121473');
        }

        if ($this->timezone === null) {
            $this->timezone = \Craft::t('sunset-to-sunset', 'America/Chicago');
        }

        if ($this->guard === null) {
            $this->guard = \Craft::t('sunset-to-sunset', 30);
        }

        if ($this->showMessageTime === null) {
            $this->showMessageTime = \Craft::t('sunset-to-sunset', 180);
        }

        if ($this->bannerCssPosition === null) {
            $this->bannerCssPosition = \Craft::t('sunset-to-sunset', 'relative');
        }
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'latitude',
                    'longitude',
                    'timezone',
                    'message',
                    'showOnSpecificUrls',
                    'bannerMessage',
                    'bannerCssPosition',
                    'bannerCssBackgroundColor',
                    'simulateTime',
                ],
                'string'
            ],
            [
                [
                    'guard',
                    'showMessageTime'
                ],
                'integer'
            ],
            [
                [
                    'latitude',
                    'longitude'
                ],
                'required'
            ],
        ];
    }
}
