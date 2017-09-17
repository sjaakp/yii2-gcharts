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


class BubbleChart extends Chart    {

    public function init()  {
        parent::init();

        $dataTable = $this->dataTable();

        $jOpts = self::encode($this->options);

        $id = $this->getId();

        $this->loadPackages('corechart');

        $this->drawChart("var $id=new google.visualization.BubbleChart(document.getElementById('$id'));$id.draw($dataTable,$jOpts);");
    }
}