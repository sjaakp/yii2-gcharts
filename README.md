yii2-gcharts
============

#### Google Charts the Yii 2.0 way ####

**yii2-gcharts** is a collection of widgets to render selected [Google Charts](https://developers.google.com/chart/ "Google Developers") in the [Yii 2.0](http://www.yiiframework.com/ "Yii") PHP Framework, like you would use a [GridView](http://www.yiiframework.com/doc-2.0/yii-grid-gridview.html "Yii").

Currently, **yii2-gcharts** consists of the following widgets:

- [AreaChart](https://developers.google.com/chart/interactive/docs/gallery/areachart "Google Developers")
- [BarChart](https://developers.google.com/chart/interactive/docs/gallery/barchart "Google Developers")
- [BubbleChart](https://developers.google.com/chart/interactive/docs/gallery/bubblechart "Google Developers")
- [ColumnChart](https://developers.google.com/chart/interactive/docs/gallery/columnchart "Google Developers")
- [GeoChart](https://developers.google.com/chart/interactive/docs/gallery/geochart "Google Developers")
- [LineChart](https://developers.google.com/chart/interactive/docs/gallery/linechart "Google Developers")
- [PieChart](https://developers.google.com/chart/interactive/docs/gallery/piechart "Google Developers")
- [ScatterChart](https://developers.google.com/chart/interactive/docs/gallery/scatterchart "Google Developers")

A demonstration of **Yii2-gcharts** is [here](http://www.sjaakpriester.nl/software/yii2-gcharts).

## Installation ##

Install **yii2-gcharts** with [Composer](https://getcomposer.org/). Either add the following to the require section of your `composer.json` file:

`"sjaakp/yii2-gcharts": "*"` 

Or run:

`composer require sjaakp/yii2-gcharts "*"` 

You can manually install **yii2-gcharts** by [downloading the source in ZIP-format](https://github.com/sjaakp/yii2-gcharts/archive/master.zip).

## Using yii2-gcharts ##

Use the **yii2 charts** widgets just like you would use a [GridView](http://www.yiiframework.com/doc-2.0/yii-grid-gridview.html "Yii Framework"). For instance, in the Controller you might have something like:

	<?php
	// ...
	public function actionPie()	{
		$dataProvider = new ActiveDataProvider([
			'query' => Country::find(),
		    'pagination' => false
		]);
		
		return $this->render('pie', [
			'dataProvider' => $dataProvider
		]);
	}
	// ...
	?>

To render a PieChart in the View we could use:

	<?php
	use sjaakp\gcharts\PieChart;
	?>
	...
    <?= PieChart::widget([
        'height' => '400px',
        'dataProvider' => $dataProvider,
        'columns' => [
            'name:string',
            'population'
        ],
        'options' => [
            'title' => 'Countries by Population'
        ],
    ]) ?>
	...

Each of the chart types has slight variations in the column interpretation, and its own set of options. Consult the [Google Charts documentation](https://developers.google.com/chart/?hl=nl "Google Developers"). 

## Options ##

All the **yii2-gcharts** widgets share the same options:

### dataProvider ###

The data provider for the chart. This property is required. In most cases, it will be an `ActiveDataProvider` or an `ArrayDataProvider`.

### columns ###

Chart column configuration array. Each array element configures one chart column. Each column configuration is an `array` or a `string` shortcut.

An `array` column configuration can have the following members (all are optional, but at least one must be given):

- **attribute** The attribute name associated with this column. When `value` is not specified, the value of the attribute will be retrieved from each data model.
 
- **formatted** The Google Charts formatted version of the data. Can be a callable of the form `function($model, $attribute, $index, $widget)`.
 
- **label** The label assigned to the data. If not given, it is either retrieved from the model or derived from `attribute`.
 
- **pattern** The Google Charts [pattern](https://developers.google.com/chart/interactive/docs/querylanguage#Format "Google Developers").

- **role** The Google Charts [role](https://developers.google.com/chart/interactive/docs/roles "Google Developers"). Can be one of:

	- `"annotation"`
	- `"annotationText"`
	- `"certainty"`
	- `"emphasis"`
	- `"interval"`
	- `"scope"`
	- `"style"`
	- `"tooltip"`
 
- **type** The Google Charts [data type](https://developers.google.com/chart/interactive/docs/reference#DataTable "Google Developers"). Can be one of:

	- `"number"` (default)
	- `"string"`
	- `"boolean"`
	- `"date"`
	- `"datetime"`
	- `"timeofday"`

 
- **value** The data value. This can be a callable of the form `function($model, $attribute, $index, $widget)`. If not given, the value of the model's `attribute` is taken. 

The `string` shortcut configuration specifies the attribute, type, and label in the format `"attribute:type:label"`. Both type and label are optional; they take their default values if omitted.

### mode ###

`string` This determines which variant of the chart is drawn. Must be one of:

 - `"classic"` (default) Draws the 'ordinary' version of the chart,
 - `"material"` Draws the new, Material version of the chart, if available,
 - `"transition"` Draws the Material version, if available, and also applies `convertOptions()` to the options

**Notice** that only a few of the charts are currently available in Material version and that they're in early beta, lacking lots of the 'classic' options.

**Notice also** that currently, the Material options are undocumented, so the only practical way to work with Material charts is using the `"transition"` mode.

### version ###

`string` The version of the **gcharts** library used. Must be one of:

 - `"current"` (default),
 - `"upcoming"`,
 - `number`

[More](https://developers.google.com/chart/interactive/docs/basic_load_libs#load-version-name-or-number) information.

### mapsApiKey ###

`string` Applies to **GeoChart** only. It is advised to provide **GeoChart** with an [API-key](https://developers.google.com/chart/interactive/docs/gallery/geochart#loading) of Google Maps.
