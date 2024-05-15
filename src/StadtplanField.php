<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace wienermelange\stadtplan;

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
    public $lng = 16.40228105;
    public $lat = 48.25433339;

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
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
                'label' => Craft::t('app', 'Center Latiude'),
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
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value !== '' ? $value : null;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return $value !== null ? StringHelper::idnToUtf8Email($value) : null;
    }

    /**
     * @inheritdoc
     */
    protected function inputHtml($value, ElementInterface $element = null): string
    {
        $mapClass = "map-" . Html::id();

        return
            Html::cssFile("/wienermelange/wc-v1/css/wiener-melange.tokens.min.css") .
            Html::jsFile("/wienermelange/wc-v1/js/components/Map/Map.js", ["type" => "module"]) .
            Html::jsFile("/wienermelange/wc-v1/js/components/Icon/Icon.js", ["type" => "module"]) .
            Html::jsFile("/wienermelange/wc-v1/js/components/Input/Input.js", ["type" => "module"]) .
            Html::jsFile("/wienermelange/wc-v1/js/components/Stack/Stack.js", ["type" => "module"]) .
            Html::encodeParams('
                <wm-map id="{id}" class="{class}" center="{lng}, {lat}" zoom="{zoom}" style="--map-ratio: 16 / 9; font-family: var(--wm-font-stack);" controls></wm-map>
            ',
                [
                    'id' => $this->getInputId(),
                    'class' => $mapClass,
                    'zoom' => $this->zoom,
                    'lng' => $this->lng,
                    'lat' => $this->lat,
                ]
            ) .
            Html::id() .
            Html::script('
            document.querySelector(".' . $mapClass . '").addEventListener("wm-map-marker-submit", e => {
                // Set Name
                document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressName\"]").value = e.detail.text
                
                // Set lat and long
                document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressLong\"]").value = e.detail.lng
                document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressLat\"]").value = e.detail.lat

                if (e.detail.address) {
                    document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressStreet\"]").value = e.detail.address

                    if (e.detail.address.split(" ").length > 1) {
                        document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressStreet\"]").value = e.detail.address.split(" ").slice(0, -1).join(" ")
                        document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressNumber\"]").value = e.detail.address.split(" ")[e.detail.address.split(" ").length - 1]
                    } else {
                        document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressNumber\"]").value = ""
                    }
                } else {
                    document.querySelector(".' . $mapClass . '").closest("[data-id]").querySelector("input[id*=\"fields-addressStreet\"]").value = ""
                }
            })
        ');
    }



    /**
     * @inheritdoc
     */
    public function getElementValidationRules(): array
    {
        return [
            ['trim'],
            ['email', 'enableIDN' => App::supportsIdn(), 'enableLocalIDN' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        if (!$value) {
            return '';
        }
        $value = Html::encode($value);
        return "<a href=\"mailto:{$value}\">{$value}</a>";
    }
}
