<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\PieChart();
$graph->palette = new \Ezc\Graph\Palette\EzRed();
$graph->title = 'Access statistics';

$graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Mozilla' => 19113,
    'Explorer' => 10917,
    'Opera' => 1464,
    'Safari' => 652,
    'Konqueror' => 474,
) );

$graph->background->image = 'ez.png';
$graph->background->position = ezcGraph::BOTTOM | ezcGraph::RIGHT;
$graph->background->repeat = ezcGraph::NO_REPEAT;

$graph->render( 400, 150, 'tutorial_chart_background.svg' );

?>
