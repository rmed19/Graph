<?php
/**
 * File containing the AxisExactLabelRenderer class
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
use Ezc\Graph\Interfaces\AbstractAxisLabelRenderer;
use Ezc\Graph\Element\AbstractChartElementAxis;
use Ezc\Graph\Structs\Coordinate;

/**
 * Renders axis labels like known from charts drawn in analysis
 *
 * <code>
 *   $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
 * </code>
 *
 * @property bool $showLastValue
 *           Show the last value on the axis, which will be aligned different 
 *           than all other values, to not interfere with the arrow head of 
 *           the axis.
 * @property bool $renderLastOutside
 *           Render the last label outside of the normal axis label boundings
 *           next to the chart boundings. This may interfere with axis labels
 *           or cause small font size with a low axisSpace.
 *
 * @version //autogentag//
 * @package Graph
 * @mainclass
 */
class AxisExactLabelRenderer extends AbstractAxisLabelRenderer
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
        $this->properties['showLastValue']     =  true;
        $this->properties['renderLastOutside'] =  false;

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
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'showLastValue':
            case 'renderLastOutside':
                if ( !is_bool( $propertyValue ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'bool' );
                }

                $this->properties[$propertyName] = (bool) $propertyValue;
                break;
            default:
                return parent::__set( $propertyName, $propertyValue );
        }
    }

    /**
     * Render Axis labels
     *
     * Render labels for an axis.
     *
     * @param ezcGraphRenderer $renderer Renderer used to draw the chart
     * @param ezcGraphBoundings $boundings Boundings of the axis
     * @param Coordinate $start Axis starting point
     * @param Coordinate $end Axis ending point
     * @param AbstractChartElementAxis $axis Axis instance
     * @return void
     */
    public function renderLabels(
        ezcGraphRenderer $renderer,
        ezcGraphBoundings $boundings,
        Coordinate $start,
        Coordinate $end,
        AbstractChartElementAxis $axis,
        ezcGraphBoundings $innerBoundings = null )
    {
        // receive rendering parameters from axis
        $steps = $axis->getSteps();

        $axisBoundings = new ezcGraphBoundings(
            $start->x, $start->y,
            $end->x, $end->y
        );

        // Determine normalized axis direction
        $direction = new ezcGraphVector(
            $end->x - $start->x,
            $end->y - $start->y
        );
        $direction->unify();
        
        // Get axis space
        $gridBoundings = null;
        list( $xSpace, $ySpace ) = $this->getAxisSpace( $renderer, $boundings, $axis, $innerBoundings, $gridBoundings );

        // Draw steps and grid
        foreach ( $steps as $nr => $step )
        {
            $position = new Coordinate(
                $start->x + ( $end->x - $start->x ) * $step->position,
                $start->y + ( $end->y - $start->y ) * $step->position
            );
            $stepSize = new Coordinate(
                $axisBoundings->width * $step->width,
                $axisBoundings->height * $step->width
            );

            if ( ! $step->isZero )
            {
                // major grid
                if ( $axis->majorGrid )
                {
                    $this->drawGrid( 
                        $renderer, 
                        $gridBoundings, 
                        $position,
                        $stepSize,
                        $axis->majorGrid
                    );
                }
                
                // major step
                $this->drawStep( 
                    $renderer, 
                    $position,
                    $direction, 
                    $axis->position, 
                    $this->majorStepSize, 
                    $axis->border
                );
            }

            if ( $this->showLabels )
            {
                switch ( $axis->position )
                {
                    case ezcGraph::RIGHT:
                    case ezcGraph::LEFT:
                        $labelWidth = $axisBoundings->width * 
                            $steps[$nr - $step->isLast]->width /
                            ( $this->showLastValue + 1 );
                        $labelHeight = $ySpace;

                        if ( ( $this->renderLastOutside === true ) &&
                             ( $step->isLast === true ) )
                        {
                            $labelWidth = ( $boundings->width - $axisBoundings->width ) / 2;
                        }
                        break;

                    case ezcGraph::BOTTOM:
                    case ezcGraph::TOP:
                        $labelWidth = $xSpace;
                        $labelHeight = $axisBoundings->height * 
                            $steps[$nr - $step->isLast]->width /
                            ( $this->showLastValue + 1 );

                        if ( ( $this->renderLastOutside === true ) &&
                             ( $step->isLast === true ) )
                        {
                            $labelHeight = ( $boundings->height - $axisBoundings->height ) / 2;
                        }
                        break;
                }

                $showLabel = true;
                switch ( true )
                {
                    case ( !$this->showLastValue && $step->isLast ):
                        // Skip last step if showLastValue is false
                        $showLabel = false;
                        break;
                    // Draw label at top left of step
                    case ( ( $axis->position === ezcGraph::BOTTOM ) &&
                           ( !$step->isLast ) ) ||
                         ( ( $axis->position === ezcGraph::BOTTOM ) &&
                           ( $step->isLast ) &&
                           ( $this->renderLastOutside ) ) ||
                         ( ( $axis->position === ezcGraph::TOP ) &&
                           ( $step->isLast ) &&
                           ( !$this->renderLastOutside ) ):
                        $labelBoundings = new ezcGraphBoundings(
                            $position->x - $labelWidth + $this->labelPadding,
                            $position->y - $labelHeight + $this->labelPadding,
                            $position->x - $this->labelPadding,
                            $position->y - $this->labelPadding
                        );
                        $alignement = ezcGraph::RIGHT | ezcGraph::BOTTOM;
                        break;
                    // Draw label at bottom right of step
                    case ( ( $axis->position === ezcGraph::LEFT ) &&
                           ( !$step->isLast ) ) ||
                         ( ( $axis->position === ezcGraph::LEFT ) &&
                           ( $step->isLast ) &&
                           ( $this->renderLastOutside ) ) ||
                         ( ( $axis->position === ezcGraph::RIGHT ) &&
                           ( $step->isLast ) &&
                           ( !$this->renderLastOutside ) ):
                        $labelBoundings = new ezcGraphBoundings(
                            $position->x + $this->labelPadding,
                            $position->y + $this->labelPadding,
                            $position->x + $labelWidth - $this->labelPadding,
                            $position->y + $labelHeight - $this->labelPadding
                        );
                        $alignement = ezcGraph::LEFT | ezcGraph::TOP;
                        break;
                    // Draw label at bottom left of step
                    case ( ( $axis->position === ezcGraph::TOP ) &&
                           ( !$step->isLast ) ) ||
                         ( ( $axis->position === ezcGraph::TOP ) &&
                           ( $step->isLast ) &&
                           ( $this->renderLastOutside ) ) ||
                         ( ( $axis->position === ezcGraph::RIGHT ) &&
                           ( !$step->isLast ) ) ||
                         ( ( $axis->position === ezcGraph::RIGHT ) &&
                           ( $step->isLast ) &&
                           ( $this->renderLastOutside ) ) ||
                         ( ( $axis->position === ezcGraph::BOTTOM ) &&
                           ( $step->isLast ) &&
                           ( !$this->renderLastOutside ) ) ||
                         ( ( $axis->position === ezcGraph::LEFT ) &&
                           ( $step->isLast ) &&
                           ( !$this->renderLastOutside ) ):
                        $labelBoundings = new ezcGraphBoundings(
                            $position->x - $labelWidth + $this->labelPadding,
                            $position->y + $this->labelPadding,
                            $position->x - $this->labelPadding,
                            $position->y + $labelHeight - $this->labelPadding
                        );
                        $alignement = ezcGraph::RIGHT | ezcGraph::TOP;
                        break;
                }

                if ( $showLabel )
                {
                    $renderer->drawText(
                        $labelBoundings,
                        $step->label,
                        $alignement
                    );
                }
            }

            if ( !$step->isLast )
            {
                // Iterate over minor steps
                foreach ( $step->childs as $minorStep )
                {
                    $minorStepPosition = new Coordinate(
                        $start->x + ( $end->x - $start->x ) * $minorStep->position,
                        $start->y + ( $end->y - $start->y ) * $minorStep->position
                    );
                    $minorStepSize = new Coordinate(
                        $axisBoundings->width * $minorStep->width,
                        $axisBoundings->height * $minorStep->width
                    );

                    if ( $axis->minorGrid )
                    {
                        $this->drawGrid( 
                            $renderer, 
                            $gridBoundings, 
                            $minorStepPosition,
                            $minorStepSize,
                            $axis->minorGrid
                        );
                    }
                    
                    // major step
                    $this->drawStep( 
                        $renderer, 
                        $minorStepPosition,
                        $direction, 
                        $axis->position, 
                        $this->minorStepSize, 
                        $axis->border
                    );
                }
            }
        }
    }
}
?>