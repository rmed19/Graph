<?php

require_once 'tutorial_autoload.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\LineChart();
$graph->title = 'Some random data';
$graph->legend = false;

$graph->xAxis = new \Ezc\Graph\Axis\ChartElementNumericAxis();

$graph->xAxis->min = -15;
$graph->xAxis->max = 15;
$graph->xAxis->majorStep = 5;

$data = array(
    array(),
    array()
);
for ( $i = -10; $i <= 10; $i++ )
{
    $data[0][$i] = mt_rand( -23, 59 );
    $data[1][$i] = mt_rand( -23, 59 );
}

// Add data
$graph->data['random blue'] = new \Ezc\Graph\Datasets\ArrayDataSet( $data[0] );
$graph->data['random green'] = new \Ezc\Graph\Datasets\ArrayDataSet( $data[1] );

$graph->render( 400, 150, 'tutorial_axis_numeric.svg' );

?>
