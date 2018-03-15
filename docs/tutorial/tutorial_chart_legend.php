<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\PieChart();
$graph->palette = new \Ezc\Graph\Palette\EzGreen();
$graph->title = 'Access statistics';

$graph->legend = false;

$graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Mozilla' => 19113,
    'Explorer' => 10917,
    'Opera' => 1464,
    'Safari' => 652,
    'Konqueror' => 474,
) );

$graph->render( 400, 150, 'tutorial_chart_legend.svg' );

?>
