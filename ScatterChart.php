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


class ScatterChart extends Chart    {

    public function init()  {
        parent::init();

        $dataTable = $this->dataTable();

        $jOpts = self::encode($this->options);

        $id = $this->getId();

        if ($this->mode == 'classic') {
            $package = 'corechart';
            $call = "var $id=new google.visualization.ScatterChart(document.getElementById('$id'));$id.draw($dataTable,$jOpts);";
        }
        else    {
            $package = 'scatter';
            if ($this->mode == 'transition') $jOpts = "google.charts.Scatter.convertOptions($jOpts)";
            $call = "var $id=new google.charts.Scatter(document.getElementById('$id'));$id.draw($dataTable,$jOpts);";
        }

        $this->loadPackages($package);

        $this->drawChart($call);
    }
}
