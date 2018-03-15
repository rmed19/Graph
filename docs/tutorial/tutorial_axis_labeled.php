<?php

require_once 'tutorial_autoload.php';

use Ezc\Graph\Axis\ChartElementLabeledAxis;
use Ezc\Graph\Charts\LineChart;

$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new LineChart();
$graph->options->fillLines = 210;
$graph->options->font->maxFontSize = 10;
$graph->title = 'Error level colors';
$graph->legend = false;

$graph->yAxis = new ChartElementLabeledAxis();
$graph->yAxis->axisLabelRenderer->showZeroValue = true;

$graph->yAxis->label = 'Color';
$graph->xAxis->label = 'Error level';

// Add data
$graph->data['colors'] = new \Ezc\Graph\Datasets\ArrayDataSet(
    array(
        'info' => 'blue',
        'notice' => 'green',
        'warning' => 'orange',
        'error' => 'red',
        'fatal' => 'red',
    )
);

$graph->render( 400, 150, 'tutorial_axis_labeled.svg' );

?>
