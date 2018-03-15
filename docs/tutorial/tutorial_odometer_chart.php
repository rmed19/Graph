<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\OdometerChart();
$graph->title = 'Sample odometer';

$graph->options->font->maxFontSize = 12;

$graph->data['data'] = new \Ezc\Graph\Datasets\ArrayDataSet(
    array( 1, 3, 9 )
);

$graph->render( 400, 150, 'tutorial_odometer_chart.svg' );

?>
