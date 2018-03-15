<?php

require_once 'tutorial_autoload.php';

use Ezc\Graph\Axis\ChartElementDateAxis;
use Ezc\Graph\Charts\LineChart;

$graph = new LineChart();
$graph->options->fillLines = 210;
$graph->title = 'Concurrent requests';
$graph->legend = false;

$graph->xAxis = new ChartElementDateAxis();

// Add data
$graph->data['Machine 1'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    '8:00' => 3241,
    '8:13' => 934,
    '8:24' => 1201,
    '8:27' => 1752,
    '8:51' => 123,
) );
$graph->data['Machine 2'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    '8:05' => 623,
    '8:12' => 2103,
    '8:33' => 543,
    '8:43' => 2034,
    '8:59' => 3410,
) );

$graph->data['Machine 1']->symbol = ezcGraph::BULLET;
$graph->data['Machine 2']->symbol = ezcGraph::BULLET;

$graph->render( 400, 150, 'tutorial_axis_datetime.svg' );

?>
