<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\LineChart();
$graph->title = 'Sinus';
$graph->legend->position = ezcGraph::BOTTOM;

$graph->xAxis = new \Ezc\Graph\Axis\ChartElementNumericAxis();

$graph->data['sinus'] = new \Ezc\Graph\Datasets\NumericDataSet(
    -360, // Start value
    360,  // End value
    create_function(
        '$x',
        'return sin( deg2rad( $x ) );'
    )
);

$graph->data['sinus']->resolution = 120;

$graph->render( 400, 150, 'tutorial_dataset_numeric.svg' );

?>
