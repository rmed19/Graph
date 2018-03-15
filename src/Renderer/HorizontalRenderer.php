<?php
/**
 * File containing the two dimensional renderer
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

namespace Ezc\Graph\Renderer;

use Ezc\Graph\Colors\Color;
use Ezc\Graph\Structs\Context;
use Ezc\Graph\Structs\Coordinate;

/**
 * Class to transform horizontal bar charts primitives into image primitives.
 * Renders charts in a two dimensional view.
 *
 * The class options are defined in the class {@link \Ezc\Graph\Options\Renderer2dOptions}
 * extending the basic renderer options in {@link \Ezc\Graph\Options\RendererOptions}.
 *
 * <code>
 *  @TODO: Add example
 * </code>
 *
 * @version //autogentag//
 * @package Graph
 * @mainclass
 */
class HorizontalRenderer 
    extends
        Renderer2d
    implements
        ezcGraphHorizontalBarRenderer
{
    /**
     * Draw horizontal bar
     *
     * Draws a horizontal bar as a data element in a line chart
     * 
     * @param ezcGraphBoundings $boundings Chart boundings
     * @param Context $context Context of call
     * @param Color $color Color of line
     * @param Coordinate $position Position of data point
     * @param float $stepSize Space which can be used for bars
     * @param int $dataNumber Number of dataset
     * @param int $dataCount Count of datasets in chart
     * @param int $symbol Symbol to draw for line
     * @param float $axisPosition Position of axis for drawing filled lines
     * @return void
     */
    public function drawHorizontalBar(
        ezcGraphBoundings $boundings,
        Context $context,
        Color $color,
        Coordinate $position,
        $stepSize,
        $dataNumber = 1,
        $dataCount = 1,
        $symbol = ezcGraph::NO_SYMBOL,
        $axisPosition = 0. )
    {
        // Apply margin
        $margin = $stepSize * $this->options->barMargin;
        $padding = $stepSize * $this->options->barPadding;
        $barHeight = ( $stepSize - $margin ) / $dataCount - $padding;
        $offset = - $stepSize / 2 + $margin / 2 + ( $dataCount - $dataNumber - 1 ) * ( $padding + $barHeight ) + $padding / 2;

        $barPointArray = array(
            new Coordinate(
                $boundings->x0 + ( $boundings->width ) * $axisPosition,
                $boundings->y0 + ( $boundings->height ) * $position->y + $offset
            ),
            new Coordinate(
                $boundings->x0 + ( $boundings->width ) * $position->x,
                $boundings->y0 + ( $boundings->height ) * $position->y + $offset
            ),
            new Coordinate(
                $boundings->x0 + ( $boundings->width ) * $position->x,
                $boundings->y0 + ( $boundings->height ) * $position->y + $offset + $barHeight
            ),
            new Coordinate(
                $boundings->x0 + ( $boundings->width ) * $axisPosition,
                $boundings->y0 + ( $boundings->height ) * $position->y + $offset + $barHeight
            ),
        );

        $this->addElementReference(
            $context,
            $this->driver->drawPolygon(
                $barPointArray,
                $color,
                true
            )
        );

        if ( $this->options->dataBorder > 0 )
        {
            $darkened = $color->darken( $this->options->dataBorder );
            $this->driver->drawPolygon(
                $barPointArray,
                $darkened,
                false,
                1
            );
        }
    }
    
    /**
     * Draw bar
     *
     * Draws a bar as a data element in a line chart
     * 
     * @param ezcGraphBoundings $boundings Chart boundings
     * @param Context $context Context of call
     * @param Color $color Color of line
     * @param Coordinate $position Position of data point
     * @param float $stepSize Space which can be used for bars
     * @param int $dataNumber Number of dataset
     * @param int $dataCount Count of datasets in chart
     * @param int $symbol Symbol to draw for line
     * @param float $axisPosition Position of axis for drawing filled lines
     * @return void
     */
    public function drawBar(
        ezcGraphBoundings $boundings,
        Context $context,
        Color $color,
        Coordinate $position,
        $stepSize,
        $dataNumber = 1,
        $dataCount = 1,
        $symbol = ezcGraph::NO_SYMBOL,
        $axisPosition = 0. )
    {
        throw new ezcBaseFunctionalityNotSupportedException(
            "A normal bar chart",
            "Only horizontal bar charts can be renderered with the HorizontalRenderer"
        );
    }
    
    /**
     * Draw data line
     *
     * Draws a line as a data element in a line chart
     * 
     * @param ezcGraphBoundings $boundings Chart boundings
     * @param Context $context Context of call
     * @param Color $color Color of line
     * @param Coordinate $start Starting point
     * @param Coordinate $end Ending point
     * @param int $dataNumber Number of dataset
     * @param int $dataCount Count of datasets in chart
     * @param int $symbol Symbol to draw for line
     * @param Color $symbolColor Color of the symbol, defaults to linecolor
     * @param Color $fillColor Color to fill line with
     * @param float $axisPosition Position of axis for drawing filled lines
     * @param float $thickness Line thickness
     * @return void
     */
    public function drawDataLine(
        ezcGraphBoundings $boundings,
        Context $context,
        Color $color,
        Coordinate $start,
        Coordinate $end,
        $dataNumber = 1,
        $dataCount = 1,
        $symbol = ezcGraph::NO_SYMBOL,
        Color $symbolColor = null,
        Color $fillColor = null,
        $axisPosition = 0.,
        $thickness = 1. )
    {
        throw new ezcBaseFunctionalityNotSupportedException(
            "A normal line chart",
            "Only horizontal bar charts can be renderered with the HorizontalRenderer"
        );
    }
}

?>
