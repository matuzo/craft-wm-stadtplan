<?php

namespace matuzo\wmstadtplan;

use Craft;
use craft\events\RegisterComponentTypesEvent;
use matuzo\wmstadtplan\StadtplanField;
use matuzo\wmstadtplan\StadtplanBundle;
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

    Craft::$app->view->hook('cp.entries.edit.content', function (array &$context) {
      $this->view->registerAssetBundle(StadtplanBundle::class);
    });
  }
}