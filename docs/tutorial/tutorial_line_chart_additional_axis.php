<?php

require_once 'tutorial_autoload.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\LineChart();
$graph->title = 'Wikipedia articles';

// Add data
foreach ( $wikidata as $language => $data )
{
    $graph->data[$language] = new \Ezc\Graph\Datasets\ArrayDataSet( $data );
}

$graph->yAxis->min = 0;

// Use a different axis for the norwegian dataset
$graph->additionalAxis['norwegian'] = $nAxis = new \Ezc\Graph\Axis\ChartElementNumericAxis();
$nAxis->position = ezcGraph::BOTTOM;
$nAxis->chartPosition = 1;
$nAxis->min = 0;

$graph->data['Norwegian']->yAxis = $nAxis;

// Still use the marker
$graph->additionalAxis['border'] = $marker = new \Ezc\Graph\Axis\ChartElementNumericAxis();

$marker->position = ezcGraph::LEFT;
$marker->chartPosition = 1 / 3;

$graph->render( 400, 150, 'tutorial_line_chart_additional_axis.svg' );

?>
