<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace matuzo\wmstadtplan;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\App;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\StringHelper;
use yii\db\Schema;

/**
 * Stadtplan represents an Stadtplan field.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0.0
 */
class StadtplanField extends Field implements PreviewableFieldInterface
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', 'Stadtplan');
    }

    /**
     * @inheritdoc
     */
    public static function valueType(): string
    {
        return 'string|null';
    }

    /**
     * @var string|null The input’s placeholder text
     */
    public $placeholder;
    public $zoom = 16;
    public $lng = 16.356551313418166;
    public $lat = 48.211151008932525;

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): string
    {
        return
            Cp::textFieldHtml([
                'label' => Craft::t('app', 'Center Longitude'),
                'instructions' => Craft::t('app', 'lng Wert für das Zentrum der Karte, wenn die geladen wird.'),
                'id' => 'lng',
                'name' => 'lng',
                'value' => $this->lng,
                'errors' => $this->getErrors('lng'),
            ]) .
            Cp::textFieldHtml([
                'label' => Craft::t('app', 'Center Latitude'),
                'instructions' => Craft::t('app', 'lat Wert für das Zentrum der Karte, wenn die geladen wird.'),
                'id' => 'lat',
                'name' => 'lat',
                'value' => $this->lat,
                'errors' => $this->getErrors('lat'),
            ]) .
            Cp::textFieldHtml([
                'label' => Craft::t('app', 'Default Zoom'),
                'instructions' => Craft::t('app', 'Zoomstufe, wenn die geladen wird.'),
                'id' => 'zoom',
                'name' => 'zoom',
                'value' => $this->zoom,
                'errors' => $this->getErrors('zoom'),
            ]);
    }

    /**
     * @inheritdoc
     */
    protected function inputHtml($value, ElementInterface $element = null): string
    {
        return
            Html::encodeParams('
                <wm-map id="{id}" center="{lng}, {lat}" zoom="{zoom}" controls></wm-map>
            ',
                [
                    'id' => $this->getInputId(),
                    'zoom' => $this->zoom,
                    'lng' => $this->lng,
                    'lat' => $this->lat,
                ]
            );
    }
}
