<?php

namespace matuzo\wmstadtplan;

use Craft;
use craft\events\RegisterComponentTypesEvent;
use matuzo\wmstadtplan\StadtplanField;
use matuzo\wmstadtplan\StadtplanBundle;
use craft\services\Fields;
use yii\base\Event;
use craft\web\View;
use craft\events\TemplateEvent;

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
  

    Event::on(
      View::class,
      View::EVENT_BEFORE_RENDER_PAGE_TEMPLATE,
      static function (TemplateEvent $event) {
          if ($event->templateMode !== View::TEMPLATE_MODE_CP) {
              return;
          }
          Craft::$app->getView()->registerAssetBundle(StadtplanBundle::class);
      }
  );
  }
}