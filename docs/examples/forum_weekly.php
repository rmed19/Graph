<?php

require 'Base/src/base.php';
function __autoload( $className )
{
        ezcBase::autoload( $className );
}

// Create the graph
$graph = new \Ezc\Graph\Charts\PieChart();
$graph->palette = new \Ezc\Graph\Palette\EzBlue();
$graph->legend = false;

// Add the data and hilight norwegian data set
$graph->data['week'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Claudia Kosny' => 45,
    'Lukasz Serwatka' => 35,
    'Kristof Coomans' => 25,
    'David Jones' => 23,
    'Xavier Dutoit' => 20,
    'sangib das' => 14,
    'Mark Marsiglio' => 10,
    'mark hayhurst' => 10,
    'Paul Borgermans' => 10,
    'Nabil Alimi' => 9,
) );

// Set graph title
$graph->title = '10 most active users on forum in last week';

// Use 3d renderer, and beautify it
$graph->renderer = new \Ezc\Graph\Renderer\Renderer3d();
$graph->renderer->options->pieChartShadowSize = 12;
$graph->renderer->options->pieChartGleam = .5;
$graph->renderer->options->dataBorder = false;
$graph->renderer->options->pieChartHeight = 16;
$graph->renderer->options->legendSymbolGleam = .5;
$graph->renderer->options->pieChartOffset = 100;

$graph->driver = new ezcGraphSvgDriver();

// Output the graph with std SVG driver
$graph->render( 500, 200, 'forum_weekly.svg' );

?>
