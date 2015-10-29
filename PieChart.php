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


class PieChart extends Chart    {

    protected $packages = ['corechart'];

    public function init()  {
        parent::init();

        $dataTable = $this->dataTable();

        $jOpts = self::encode($this->options);

        $id = $this->getId();

        $this->getView()->registerJs("var $id=new google.visualization.PieChart(document.getElementById('$id'));$id.draw($dataTable,$jOpts);");
    }
}