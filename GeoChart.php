<?php
/**
 * MIT licence
 * Version 1.1.0
 * Sjaak Priester, Amsterdam 29-10-2015... 17-09-2017.
 *
 * Google Charts widget for Yii 2.0 framework
 * @link https://developers.google.com/chart/
 */

namespace sjaakp\gcharts;


class GeoChart extends Chart    {

    /**
     * @var string
     * GeoCharts needs a mapsApiKey (at least it is advised)
     * @link https://developers.google.com/chart/interactive/docs/gallery/geochart#loading
     */
    public $mapsApiKey;

    public function init()  {
        parent::init();

        $dataTable = $this->dataTable();

        $jOpts = self::encode($this->options);

        $id = $this->getId();

        if (!empty($this->mapsApiKey)) self::$loadOptions['mapsApiKey'] = $this->mapsApiKey;

        $this->loadPackages('geochart');

        $this->drawChart("var $id=new google.visualization.GeoChart(document.getElementById('$id'));$id.draw($dataTable,$jOpts);");
    }
}
