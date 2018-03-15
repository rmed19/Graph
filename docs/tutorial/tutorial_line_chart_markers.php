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

$graph->additionalAxis['border'] = $marker = new \Ezc\Graph\Axis\ChartElementNumericAxis( );

$marker->position = ezcGraph::LEFT;
$marker->chartPosition = 1 / 3;
$marker->label = 'One million!';

$graph->render( 400, 150, 'tutorial_line_chart_markers.svg' );

?>
