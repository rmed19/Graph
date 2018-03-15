<?php
/**
 * File containing the ezcGraphRadarRenderer interface
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
namespace Ezc\Graph\Interfaces;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Structs\Context;
use Ezc\Graph\Structs\Coordinate;

/**
 * Interface which adds the methods required for rendering radar charts to a 
 * renderer
 *
 * @version //autogentag//
 * @package Graph
 */
interface ezcGraphRadarRenderer
{
    /**
     * Draw radar chart data line
     *
     * Draws a line as a data element in a radar chart
     * 
     * @param ezcGraphBoundings $boundings Chart boundings
     * @param Context $context Context of call
     * @param Color $color Color of line
     * @param Coordinate $center Center of radar chart
     * @param Coordinate $start Starting point
     * @param Coordinate $end Ending point
     * @param int $dataNumber Number of dataset
     * @param int $dataCount Count of datasets in chart
     * @param int $symbol Symbol to draw for line
     * @param Color $symbolColor Color of the symbol, defaults to linecolor
     * @param Color $fillColor Color to fill line with
     * @param float $thickness Line thickness
     * @return void
     */
    public function drawRadarDataLine(
        ezcGraphBoundings $boundings,
        Context $context,
        Color $color,
        Coordinate $center,
        Coordinate $start,
        Coordinate $end,
        $dataNumber = 1,
        $dataCount = 1,
        $symbol = ezcGraph::NO_SYMBOL,
        Color $symbolColor = null,
        Color $fillColor = null,
        $thickness = 1.
    );
}

?>
