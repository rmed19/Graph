<?php

require 'Base/src/base.php';
function __autoload( $className )
{
    ezcBase::autoload( $className );
}

// Create the graph
$graph = new \Ezc\Graph\Charts\PieChart();

$graph->palette = new \Ezc\Graph\Palette\EzRed();

// Add the data and hilight norwegian data set
$graph->data['articles'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'English' => 1300000,
    'Germany' => 452000,
    'Netherlands' => 217000,
    'Norway' => 70000,
) );
$graph->data['articles']->highlight['Germany'] = true;

// Set graph title
$graph->title = 'Wikipedia articles by country';

// Modify pie chart label to only show amount and percent
$graph->options->label = '%2$d (%3$.1f%%)';

// Use 3d renderer, and beautify it
$graph->renderer = new \Ezc\Graph\Renderer\Renderer3d();

$graph->renderer->options->pieChartShadowSize = 12;
$graph->renderer->options->pieChartGleam = .5;
$graph->renderer->options->dataBorder = false;

$graph->renderer->options->pieChartHeight = 8;
$graph->renderer->options->pieChartRotation = .8;
$graph->renderer->options->pieChartOffset = 190;

$graph->renderer->options->legendSymbolGleam = .5;

// Output the graph with std SVG driver
$graph->render( 400, 200, 'wikipedia.svg' );

?>
