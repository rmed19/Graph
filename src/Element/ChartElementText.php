<?php
namespace Ezc\Graph\Element;
/**
 * File containing the ChartElementText class
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

use Ezc\Graph\Interfaces\AbstractChartElement;
use Ezc\Graph\Math\Boundings;

/**
 * Chart element to display texts in a chart
 *
 * Chart elements can be understood as widgets or layout container inside the
 * chart. The actual transformation to images happens inside the renderers.
 * They represent all elements inside the chart and contain mostly general
 * formatting options, while the renderer itself might define additional
 * formatting options for some chart elments. You can find more about the
 * general formatting options for chart elements in the base class
 * Ezc\Graph\Interfaces\AbstractChartElement.
 *
 * The text element can only be placed at the top or the bottom of the chart.
 * Beside the common options it has only one additional option defining the
 * maximum height used for the text box. The actaully required height is
 * calculated based on the assigned text size.
 *
 * <code>
 *  $chart = new \Ezc\Graph\Charts\PieChart();
 *  $chart->data['example'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
 *      'Foo' => 23,
 *      'Bar' => 42,
 *  ) );
 *
 *  $chart->title = 'Some pie chart';
 *
 *  // Use at maximum 5% of the chart height for the title.
 *  $chart->title->maxHeight = .05;
 *
 *  $graph->render( 400, 250, 'title.svg' );
 * </code>
 *
 * @property float $maxHeight
 *           Maximum percent of bounding used to display the text.
 *
 * @version //autogentag//
 * @package Graph
 * @mainclass
 */
class ChartElementText extends AbstractChartElement
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
        $this->properties['maxHeight'] = .1;

        parent::__construct( $options );
    }

    /**
     * __set 
     * 
     * @param mixed $propertyName 
     * @param mixed $propertyValue 
     * @throws ezcBaseValueException
     *          If a submitted parameter was out of range or type.
     * @throws ezcBasePropertyNotFoundException
     *          If a the value for the property options is not an instance of
     * @return void
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'maxHeight':
                if ( !is_numeric( $propertyValue ) ||
                     ( $propertyValue < 0 ) ||
                     ( $propertyValue > 1 ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, '0 <= float <= 1' );
                }

                $this->properties['maxHeight'] = (float) $propertyValue;
                break;
            default:
                parent::__set( $propertyName, $propertyValue );
                break;
        }
    }

    /**
     * Render the text
     * 
     * @param ezcGraphRenderer $renderer Renderer
     * @param Boundings $boundings Boundings for the axis
     * @return Boundings Remaining boundings
     */
    public function render( ezcGraphRenderer $renderer, Boundings $boundings )
    {
        $height = (int) min( 
            round( $this->properties['maxHeight'] * ( $boundings->y1 - $boundings->y0 ) ),
            $this->properties['font']->maxFontSize + $this->padding * 2 + $this->margin * 2
        );

        switch ( $this->properties['position'] )
        {
            case ezcGraph::TOP:
                $textBoundings = new Boundings(
                    $boundings->x0,
                    $boundings->y0,
                    $boundings->x1,
                    $boundings->y0 + $height
                );
                $boundings->y0 += $height + $this->properties['margin'];
                break;
            case ezcGraph::BOTTOM:
                $textBoundings = new Boundings(
                    $boundings->x0,
                    $boundings->y1 - $height,
                    $boundings->x1,
                    $boundings->y1
                );
                $boundings->y1 -= $height + $this->properties['margin'];
                break;
        }

        $textBoundings = $renderer->drawBox(
            $textBoundings,
            $this->properties['background'],
            $this->properties['border'],
            $this->properties['borderWidth'],
            $this->properties['margin'],
            $this->properties['padding']
        );

        $renderer->drawText(
            $textBoundings,
            $this->properties['title'],
            ezcGraph::CENTER | ezcGraph::MIDDLE
        );

        return $boundings;
    }
}

?>
