<?php

namespace Ezc\Graph\Charts;

/**
 * File containing the BarChart class
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Graph
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

use Ezc\Graph\Renderer\AxisBoxedLabelRenderer;

/**
 * Class for bar charts. Can make use of an unlimited amount of datasets and 
 * will display them as bars by default.
 * X axis:
 *  - Labeled axis
 *  - Boxed axis label renderer
 * Y axis:
 *  - Numeric axis
 *  - Exact axis label renderer
 *
 * <code>
 *  // Create a new line chart
 *  $chart = new BarChart();
 *
 *  // Add data to line chart
 *  $chart->data['sample dataset'] = new \Ezc\Graph\Datasets\ArrayDataSet(
 *      array(
 *          '100' => 1.2,
 *          '200' => 43.2,
 *          '300' => -34.14,
 *          '350' => 65,
 *          '400' => 123,
 *      )   
 *  );
 *
 *  // Render chart with default 2d renderer and default SVG driver
 *  $chart->render( 500, 200, 'bar_chart.svg' );
 * </code>
 *
 * Each chart consists of several chart elements which represents logical 
 * parts of the chart and can be formatted independently. The bar chart
 * consists of:
 *  - title ( {@link Ezc\Graph\Element\ChartElementText} )
 *  - legend ( {@link Ezc\Graph\Element\ChartElementLegend} )
 *  - background ( {@link Ezc\Graph\Element\ChartElementBackground} )
 *  - xAxis ( {@link  Ezc\Graph\Axis\ChartElementLabeledAxis} )
 *  - yAxis ( {@link Ezc\Graph\Axis\ChartElementNumericAxis} )
 *
 * The type of the axis may be changed and all elements can be configured by
 * accessing them as properties of the chart:
 *
 * <code>
 *  $chart->legend->position = ezcGraph::RIGHT;
 * </code>
 *
 * The chart itself also offers several options to configure the appearance. As
 * bar charts extend line charts the the extended configure options are
 * available in {@link Ezc\Graph\Options\LineChartOptions} extending the 
 * {@link \Ezc\Graph\Options\ChartOptions}.
 *
 * @property \Ezc\Graph\Options\LineChartOptions $options
 *           Chart options class
 *
 * @version //autogentag//
 * @package Graph
 * @mainclass
 */
class BarChart extends LineChart
{
 
    /**
     * Constructor
     * 
     * @param array $options Default option array
     * @return void
     * @ignore
     */
    public function __construct( array $options = array() )
    {
        parent::__construct();

        $this->elements['xAxis']->axisLabelRenderer = new AxisBoxedLabelRenderer();
    }

    /**
     * Returns the default display type of the current chart type.
     * 
     * @return int Display type
     */
    public function getDefaultDisplayType()
    {
        return ezcGraph::BAR;
    }
}
?>
