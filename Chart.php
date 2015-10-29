<?php
/**
 * MIT licence
 * Version 1.0.0
 * Sjaak Priester, Amsterdam 29-10-2015.
 *
 * Google Charts widget for Yii 2.0 framework
 * @link https://developers.google.com/chart/
 */

namespace sjaakp\gcharts;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\View;
use yii\web\JsExpression;

class Chart extends Widget  {

    /**
     * @var string
     * 'classic'
     * 'material'   Google Charts new design
     * 'transition' same as 'material', but options will be converted
     */
    public $mode = 'classic';

    /**
     * @var \yii\data\DataProviderInterface the data provider for the chart. This property is required.
     */
    public $dataProvider;

    public $columns;

    public $height = '300px';
    public $width;

    /**
     * @var array
     * Client options for Google Charts.
     * @link https://developers.google.com/chart
     */
    public $options = [];

    /**
     * @var array event => Javascript
     * @link https://developers.google.com/chart/interactive/docs/events
     */
    public $events = [];

    /**
     * @var array
     * HTML options of the chart container.
     * Use this if you want to explicitly set the ID.
     */
    public $htmlOptions = [];

    protected $packages = [];

    protected static $packagesLoaded = [];

    public function init()  {
        if (is_null($this->dataProvider)) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        if (isset($this->htmlOptions['id'])) {
            $this->setId($this->htmlOptions['id']);
        }
        else $this->htmlOptions['id'] = $this->getId();

        $style = "height:$this->height;";
        if (! empty($this->width)) $style .= "width:$this->width;";
        $this->htmlOptions['style'] = $style;

        $this->columns = array_map(function($v) {
            if (is_string($v))  {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $v, $matches)) {
                    throw new InvalidConfigException('Column must be specified in the format of "attribute", "attribute:type" or "attribute:type:label"');
                }
                $v = [
                    'attribute' => $matches[1],
                    'type' => isset($matches[3]) ? $matches[3] : 'number',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }
            return $v;
        }, $this->columns);
    }

    public function run()   {
        $view = $this->getView();

        self::$packagesLoaded = array_merge(self::$packagesLoaded, array_diff($this->packages, self::$packagesLoaded));

        $autoLoad = [
            'modules' => [
                [
                    'name' => 'visualization',
                    'version' => 1,
                    'packages' => self::$packagesLoaded
                ]
            ],
            'language' => Yii::$app->language
        ];

        $url = 'https://www.google.com/jsapi?autoload=' . Json::htmlEncode($autoLoad);

        // register for HEAD and with fixed key (__NAMESPACE__) so only newest remains
        // can't use View::registerJsFile, because it HTML-encodes the URL
        $view->jsFiles[View::POS_END][__NAMESPACE__] = "<script src='$url'></script>";

        foreach ($this->events as $evt => $code) {
            $view->registerJs("google.visualization.events.addListener($this->id,'$evt',$code);");
        }

        echo Html::tag('div', '', $this->htmlOptions);
    }

    protected function dataTable() {
        $provider = $this->dataProvider;

        $models = $provider->getModels();
        $model = reset($models);

        $cols = array_map(function($v) use ($model) {
            $r = [
                'label' => $this->colLabel($model, $v),
                'type' => $this->colType($v),
            ];
            if (isset($v['pattern'])) $r['pattern'] = $v['pattern'];
            if (isset($v['role'])) $r['p'] = [ 'role' => $v['role']];
            return $r;
        }, $this->columns);

        $index = 0;
        $rows = array_map(function($model) use(&$index) {
            $cells = array_map(function($column) use($model, &$index) {
                $r = $this->cell($model, $column, $index);
                return $r;
            }, $this->columns);
            $index++;
            return [
                'c' => $cells
            ];
        }, $models);

        $jTable = self::encode([
            'cols' => $cols,
            'rows' => $rows
        ]);
        return "new google.visualization.DataTable($jTable)";
    }

    protected function colLabel($model, $col)  {
        $label = ArrayHelper::getValue($col, 'label');
        if (is_null($label))   {    // label may be explicitly set to empty text
            $attr = $this->colAttribute($col);
            if ($model instanceof Model) $label = $model->getAttributeLabel($attr);
            else $label = Inflector::camel2words($attr);
        }
        return $label;
    }

    protected function cell($model, $col, $index)  {
        $attr = $this->colAttribute($col);
        $value = ArrayHelper::getValue($col, 'value');
        if (is_callable($value)) $value = call_user_func($value, $model, $attr, $index, $this);

        $formatted = ArrayHelper::getValue($col, 'formatted');
        if (is_callable($formatted)) $formatted = call_user_func($formatted, $model, $attr, $index, $this);
        if (is_null($value)) $value = ArrayHelper::getValue($model, $attr);
        switch ($this->colType($col)) {
            case 'string':
                break;
            case 'number':
                $value = (float) $value;
                break;
            case 'boolean':
                $value = (boolean) $value;
                break;
            default:    // 'date', 'dattime', 'timeofday'
                if (! is_numeric($value)) $value = "'$value'";
                $value = new JsExpression("new Date($value)");
                break;
        }
        $r = [ 'v' => $value ];
        if ($formatted) $r['f'] = $formatted;
        return $r;
    }

    protected function colAttribute($col)  {
        return ArrayHelper::getValue($col, 'attribute');
    }

    protected function colType($col)  {
        return ArrayHelper::getValue($col, 'type', 'number');
    }

    protected static function encode($php) {
        return empty($php) ? '{}': preg_replace('/\"(\w+)\":/', '$1:', Json::htmlEncode($php));
    }
}
