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


class ColumnChart extends Chart    {

    public function init()  {
        parent::init();

        $dataTable = $this->dataTable();

        $jOpts = self::encode($this->options);

        $id = $this->getId();

        if ($this->mode == 'classic') {
            $package = 'corechart';
            $call = "var $id=new google.visualization.ColumnChart(document.getElementById('$id'));$id.draw($dataTable,$jOpts);";
        }
        else    {
            $package = 'bar';
            if ($this->mode == 'transition') $jOpts = "google.charts.Bar.convertOptions($jOpts)";
            $call = "var $id=new google.charts.Bar(document.getElementById('$id'));$id.draw($dataTable,$jOpts);";
        }

        $this->loadPackages($package);

        $this->drawChart($call);
    }
}
