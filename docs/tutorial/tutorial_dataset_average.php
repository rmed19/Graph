<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\LineChart();
$graph->title = 'Some random data';
$graph->legend->position = ezcGraph::BOTTOM;

$graph->xAxis = new \Ezc\Graph\Axis\ChartElementNumericAxis();

$data = array();
for ( $i = 0; $i <= 10; $i++ )
{
    $data[$i] = mt_rand( -5, 5 );
}

// Add data
$graph->data['random data'] = $dataset = new \Ezc\Graph\Datasets\ArrayDataSet( $data );

$average = new \Ezc\Graph\Datasets\AveragePolynom( $dataset, 3 );
$graph->data[(string) $average->getPolynom()] = $average;

$graph->render( 400, 150, 'tutorial_dataset_average.svg' );

?>
