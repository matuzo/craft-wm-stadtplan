<?php 

namespace matuzo\wmstadtplan;
use craft\events\RegisterComponentTypesEvent;
use wienermelange\stadtplan\StadtplanField;
use craft\services\Fields;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
              $event->types[] = StadtplanField::class;
            }
          );
    }
}