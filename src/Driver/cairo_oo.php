<?php
/**
 * File containing the ezcGraphCairoOODriver class
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

namespace Ezc\Graph\Driver;

use Ezc\Graph\Math\Boundings;
use Ezc\Graph\Options\CairoDriverOptions;
use Ezc\Graph\Options\FontOptions;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Colors\LinearGradient;
use Ezc\Graph\Colors\RadialGradient;
use Ezc\Graph\Interfaces\AbstractDriver;
use Ezc\Graph\Structs\Coordinate;

/**
 * Extension of the basic driver package to utilize the cairo library.
 *
 * This drivers options are defined in the class 
 * {@link \Ezc\Graph\Options\CairoDriverOptions} extending the basic driver options class
 * {@link \Ezc\Graph\Options\DriverOptions}. 
 *
 * As this is the default driver you do not need to explicitely set anything to
 * use it, but may use some of its advanced features.
 *
 * <code>
 *   $graph = new \Ezc\Graph\Charts\PieChart();
 *   $graph->background->color = '#FFFFFFFF';
 *   $graph->title = 'Access statistics';
 *   $graph->legend = false;
 *   
 *   $graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
 *       'Mozilla' => 19113,
 *       'Explorer' => 10917,
 *       'Opera' => 1464,
 *       'Safari' => 652,
 *       'Konqueror' => 474,
 *   ) );
 *   
 *   $graph->renderer = new \Ezc\Graph\Renderer\Renderer3d()();
 *   $graph->renderer->options->pieChartShadowSize = 10;
 *   $graph->renderer->options->pieChartGleam = .5;
 *   $graph->renderer->options->dataBorder = false;
 *   $graph->renderer->options->pieChartHeight = 16;
 *   $graph->renderer->options->legendSymbolGleam = .5;
 * 
 *   // Use cairo driver
 *   $graph->driver = new ezcGraphCairoOODriver();
 *   
 *   $graph->render( 400, 200, 'tutorial_driver_cairo.png' );
 * </code>
 *
 * @version //autogentag//
 * @package Graph
 * @mainclass
 */
class ezcGraphCairoOODriver extends AbstractDriver
{
    /**
     * Surface for cairo
     * 
     * @var CairoImageSurface
     */
    protected $surface;

    /**
     * Current cairo context.
     * 
     * @var CairoContext
     */
    protected $context;

    /**
     * List of strings to draw
     * array ( array(
     *          'text' => array( 'strings' ),
     *          'options' => FontOptions,
     *      )
     * 
     * @var array
     */
    protected $strings = array();

    /**
     * Constructor
     * 
     * @param array $options Default option array
     * @return void
     * @ignore
     */
    public function __construct( array $options = array() )
    {
        ezcBase::checkDependency( 'Graph', ezcBase::DEP_PHP_EXTENSION, 'cairo' );
        $this->options = new CairoDriverOptions( $options );
    }

    /**
     * Initilize cairo surface
     *
     * Initilize cairo surface from values provided in the options object, if
     * is has not been already initlized.
     * 
     * @return void
     */
    protected function initiliazeSurface()
    {
        // Immediatly exit, if surface already exists
        if ( $this->surface !== null )
        {
            return;
        }

        $this->surface = new CairoImageSurface(
            CairoFormat::ARGB32, 
            $this->options->width, 
            $this->options->height
        );

        $this->context = new CairoContext( $this->surface );
        $this->context->setLineWidth( 1 );
    }

    /**
     * Get SVG style definition
     *
     * Returns a string with SVG style definitions created from color, 
     * fillstatus and line thickness.
     * 
     * @param Color $color Color
     * @param mixed $filled Filled
     * @param float $thickness Line thickness.
     * @return string Formatstring
     */
    protected function getStyle( Color $color, $filled = true, $thickness = 1. )
    {
        switch ( true )
        {
            case $color instanceof LinearGradient:
                $pattern = new CairoLinearGradient(
                    $color->startPoint->x, $color->startPoint->y,
                    $color->endPoint->x, $color->endPoint->y
                );

                $pattern->addColorStopRgba(
                    0,
                    $color->startColor->red / 255,
                    $color->startColor->green / 255,
                    $color->startColor->blue / 255,
                    1 - $color->startColor->alpha / 255
                );

                $pattern->addColorStopRgba(
                    1,
                    $color->endColor->red / 255,
                    $color->endColor->green / 255,
                    $color->endColor->blue / 255,
                    1 - $color->endColor->alpha / 255
                );

                $this->context->setSource( $pattern );
                $this->context->fill();
                break;

            case $color instanceof RadialGradient:
                $pattern = new CairoRadialGradient(
                    0, 0, 0,
                    0, 0, 1
                );

                $pattern->addColorStopRgba(
                    0,
                    $color->startColor->red / 255,
                    $color->startColor->green / 255,
                    $color->startColor->blue / 255,
                    1 - $color->startColor->alpha / 255
                );

                $pattern->addColorStopRgba(
                    1,
                    $color->endColor->red / 255,
                    $color->endColor->green / 255,
                    $color->endColor->blue / 255,
                    1 - $color->endColor->alpha / 255
                );

                // Scale pattern, and move it to the correct position
                $matrix = CairoMatrix::multiply(
                    $move = CairoMatrix::initTranslate( -$color->center->x, -$color->center->y ),
                    $scale = CairoMatrix::initScale( 1 / $color->width, 1 / $color->height )
                );
                $pattern->setMatrix( $matrix );

                $this->context->setSource( $pattern );
                $this->context->fill();
                break;
            default:
                $this->context->setSourceRgba(
                    $color->red / 255,
                    $color->green / 255,
                    $color->blue / 255,
                    1 - $color->alpha / 255
                );
                break;
        }

        // Set line width
         $this->context->setLineWidth( $thickness );

        // Set requested fill state for context
        if ( $filled )
        {
            $this->context->fillPreserve();
        }
    }

    /**
     * Draws a single polygon. 
     * 
     * @param array $points Point array
     * @param Color $color Polygon color
     * @param mixed $filled Filled
     * @param float $thickness Line thickness
     * @return void
     */
    public function drawPolygon( array $points, Color $color, $filled = true, $thickness = 1. )
    {
        $this->initiliazeSurface();

        $this->context->newPath();

        $lastPoint = end( $points );
        $this->context->moveTo( $lastPoint->x, $lastPoint->y );

        foreach ( $points as $point )
        {
            $this->context->lineTo( $point->x, $point->y );
        }

        $this->context->closePath();

        $this->getStyle( $color, $filled, $thickness );
        $this->context->stroke();

        return $points;
    }
    
    /**
     * Draws a line 
     * 
     * @param Coordinate $start Start point
     * @param Coordinate $end End point
     * @param Color $color Line color
     * @param float $thickness Line thickness
     * @return void
     */
    public function drawLine( Coordinate $start, Coordinate $end, Color $color, $thickness = 1. )
    {
        $this->initiliazeSurface();

        $this->context->newPath();

        $this->context->moveTo( $start->x, $start->y );
        $this->context->lineTo( $end->x, $end->y );

        $this->getStyle( $color, false, $thickness );
        $this->context->stroke();

        return array( $start, $end );
    }

    /**
     * Returns boundings of text depending on the available font extension
     * 
     * @param float $size Textsize
     * @param FontOptions $font Font
     * @param string $text Text
     * @return Boundings Boundings of text
     */
    protected function getTextBoundings( $size, FontOptions $font, $text )
    {
        $this->context->selectFontFace( $font->name, CairoFontSlant::NORMAL, CairoFontWeight::NORMAL );
        $this->context->setFontSize( $size );
        $extents = $this->context->textExtents( $text );

        return new Boundings(
            0,
            0,
            $extents['width'],
            $extents['height']
        );
    }

    /**
     * Writes text in a box of desired size
     * 
     * @param string $string Text
     * @param Coordinate $position Top left position
     * @param float $width Width of text box
     * @param float $height Height of text box
     * @param int $align Alignement of text
     * @param ezcGraphRotation $rotation
     * @return void
     */
    public function drawTextBox( $string, Coordinate $position, $width, $height, $align, ezcGraphRotation $rotation = null )
    {
        $this->initiliazeSurface();

        $padding = $this->options->font->padding + ( $this->options->font->border !== false ? $this->options->font->borderWidth : 0 );

        $width -= $padding * 2;
        $height -= $padding * 2;
        $textPosition = new Coordinate(
            $position->x + $padding,
            $position->y + $padding
        );

        // Try to get a font size for the text to fit into the box
        $maxSize = min( $height, $this->options->font->maxFontSize );
        $result = false;
        for ( $size = $maxSize; $size >= $this->options->font->minFontSize; )
        {
            $result = $this->testFitStringInTextBox( $string, $position, $width, $height, $size );
            if ( is_array( $result ) )
            {
                break;
            }
            $size = ( ( $newsize = $size * ( $result ) ) >= $size ? $size - 1 : floor( $newsize ) );
        }
        
        if ( !is_array( $result ) )
        {
            if ( ( $height >= $this->options->font->minFontSize ) &&
                 ( $this->options->autoShortenString ) )
            {
                $result = $this->tryFitShortenedString( $string, $position, $width, $height, $size = $this->options->font->minFontSize );
            } 
            else
            {
                throw new ezcGraphFontRenderingException( $string, $this->options->font->minFontSize, $width, $height );
            }
        }

        $this->options->font->minimalUsedFont = $size;
        $this->strings[] = array(
            'text' => $result,
            'position' => $textPosition,
            'width' => $width,
            'height' => $height,
            'align' => $align,
            'font' => $this->options->font,
            'rotation' => $rotation,
        );

        return array(
            clone $position,
            new Coordinate( $position->x + $width, $position->y ),
            new Coordinate( $position->x + $width, $position->y + $height ),
            new Coordinate( $position->x, $position->y + $height ),
        );
    }
    
    /**
     * Render text depending of font type and available font extensions
     * 
     * @param string $id 
     * @param string $text 
     * @param string $font 
     * @param Color $color 
     * @param Coordinate $position 
     * @param float $size 
     * @param float $rotation 
     * @return void
     */
    protected function renderText( $text, $font, Color $color, Coordinate $position, $size, $rotation = null )
    {
        $this->context->selectFontFace( $font, CairoFontSlant::NORMAL, CairoFontWeight::NORMAL );
        $this->context->setFontSize( $size );
        
        // Store current state of context
        $this->context->save();
        $this->context->moveTo( 0, 0 );

        if ( $rotation !== null )
        {
            // Move to the center
            $this->context->translate(
                $rotation->getCenter()->x, 
                $rotation->getCenter()->y
            );
            // Rotate around text center
            $this->context->rotate(
                deg2rad( $rotation->getRotation() ) 
            );
            // Center the text
            $this->context->translate(
                $position->x - $rotation->getCenter()->x,
                $position->y - $rotation->getCenter()->y - $size * .15
            );
        } else {
            $this->context->translate(
                $position->x,
                $position->y - $size * .15
            );
        }

        $this->context->newPath();
        $this->getStyle( $color, true );
        $this->context->showText( $text );
        $this->context->stroke();

        // Restore state of context
        $this->context->restore();
    }

    /**
     * Draw all collected texts
     *
     * The texts are collected and their maximum possible font size is 
     * calculated. This function finally draws the texts on the image, this
     * delayed drawing has two reasons:
     *
     * 1) This way the text strings are always on top of the image, what 
     *    results in better readable texts
     * 2) The maximum possible font size can be calculated for a set of texts
     *    with the same font configuration. Strings belonging to one chart 
     *    element normally have the same font configuration, so that all texts
     *    belonging to one element will have the same font size.
     * 
     * @access protected
     * @return void
     */
    protected function drawAllTexts()
    {
        $this->initiliazeSurface();

        foreach ( $this->strings as $text )
        {
            $size = $text['font']->minimalUsedFont;

            $completeHeight = count( $text['text'] ) * $size + ( count( $text['text'] ) - 1 ) * $this->options->lineSpacing;

            // Calculate y offset for vertical alignement
            switch ( true )
            {
                case ( $text['align'] & ezcGraph::BOTTOM ):
                    $yOffset = $text['height'] - $completeHeight;
                    break;
                case ( $text['align'] & ezcGraph::MIDDLE ):
                    $yOffset = ( $text['height'] - $completeHeight ) / 2;
                    break;
                case ( $text['align'] & ezcGraph::TOP ):
                default:
                    $yOffset = 0;
                    break;
            }

            $padding = $text['font']->padding + $text['font']->borderWidth / 2;
            if ( $this->options->font->minimizeBorder === true )
            {
                // Calculate maximum width of text rows
                $width = false;
                foreach ( $text['text'] as $line )
                {
                    $string = implode( ' ', $line );
                    $boundings = $this->getTextBoundings( $size, $text['font'], $string );
                    if ( ( $width === false) || ( $boundings->width > $width ) )
                    {
                        $width = $boundings->width;
                    }
                }

                switch ( true )
                {
                    case ( $text['align'] & ezcGraph::CENTER ):
                        $xOffset = ( $text['width'] - $width ) / 2;
                        break;
                    case ( $text['align'] & ezcGraph::RIGHT ):
                        $xOffset = $text['width'] - $width;
                        break;
                    case ( $text['align'] & ezcGraph::LEFT ):
                    default:
                        $xOffset = 0;
                        break;
                }

                $borderPolygonArray = array(
                    new Coordinate(
                        $text['position']->x - $padding + $xOffset,
                        $text['position']->y - $padding + $yOffset
                    ),
                    new Coordinate(
                        $text['position']->x + $padding * 2 + $xOffset + $width,
                        $text['position']->y - $padding + $yOffset
                    ),
                    new Coordinate(
                        $text['position']->x + $padding * 2 + $xOffset + $width,
                        $text['position']->y + $padding * 2 + $yOffset + $completeHeight
                    ),
                    new Coordinate(
                        $text['position']->x - $padding + $xOffset,
                        $text['position']->y + $padding * 2 + $yOffset + $completeHeight
                    ),
                );
            }
            else
            {
                $borderPolygonArray = array(
                    new Coordinate(
                        $text['position']->x - $padding,
                        $text['position']->y - $padding
                    ),
                    new Coordinate(
                        $text['position']->x + $padding * 2 + $text['width'],
                        $text['position']->y - $padding
                    ),
                    new Coordinate(
                        $text['position']->x + $padding * 2 + $text['width'],
                        $text['position']->y + $padding * 2 + $text['height']
                    ),
                    new Coordinate(
                        $text['position']->x - $padding,
                        $text['position']->y + $padding * 2 + $text['height']
                    ),
                );
            }

            if ( $text['rotation'] !==  null )
            {
                foreach ( $borderPolygonArray as $nr => $point )
                {
                    $borderPolygonArray[$nr] = $text['rotation']->transformCoordinate( $point );
                }
            }

            if ( $text['font']->background !== false )
            {
                $this->drawPolygon( 
                    $borderPolygonArray, 
                    $text['font']->background,
                    true
                );
            }

            if ( $text['font']->border !== false )
            {
                $this->drawPolygon( 
                    $borderPolygonArray, 
                    $text['font']->border,
                    false,
                    $text['font']->borderWidth
                );
            }

            // Render text with evaluated font size
            $completeString = '';
            foreach ( $text['text'] as $line )
            {
                $string = implode( ' ', $line );
                $completeString .= $string;
                $boundings = $this->getTextBoundings( $size, $text['font'], $string );
                $text['position']->y += $size;

                switch ( true )
                {
                    case ( $text['align'] & ezcGraph::LEFT ):
                        $position = new Coordinate( 
                            $text['position']->x, 
                            $text['position']->y + $yOffset
                        );
                        break;
                    case ( $text['align'] & ezcGraph::RIGHT ):
                        $position = new Coordinate( 
                            $text['position']->x + ( $text['width'] - $boundings->width ), 
                            $text['position']->y + $yOffset
                        );
                        break;
                    case ( $text['align'] & ezcGraph::CENTER ):
                        $position = new Coordinate( 
                            $text['position']->x + ( ( $text['width'] - $boundings->width ) / 2 ), 
                            $text['position']->y + $yOffset
                        );
                        break;
                }

                // Optionally draw text shadow
                if ( $text['font']->textShadow === true )
                {
                    $this->renderText( 
                        $string,
                        $text['font']->name, 
                        $text['font']->textShadowColor,
                        new Coordinate(
                            $position->x + $text['font']->textShadowOffset,
                            $position->y + $text['font']->textShadowOffset
                        ),
                        $size,
                        $text['rotation']
                    );
                }
                
                // Finally draw text
                $this->renderText( 
                    $string,
                    $text['font']->name, 
                    $text['font']->color, 
                    $position,
                    $size,
                    $text['rotation']
                );

                $text['position']->y += $size * $this->options->lineSpacing;
            }
        }
    }

    /**
     * Draws a sector of cirlce
     * 
     * @param Coordinate $center Center of circle
     * @param mixed $width Width
     * @param mixed $height Height
     * @param mixed $startAngle Start angle of circle sector
     * @param mixed $endAngle End angle of circle sector
     * @param Color $color Color
     * @param mixed $filled Filled;
     * @return void
     */
    public function drawCircleSector( Coordinate $center, $width, $height, $startAngle, $endAngle, Color $color, $filled = true )
    {
        $this->initiliazeSurface();

        // Normalize angles
        if ( $startAngle > $endAngle )
        {
            $tmp = $startAngle;
            $startAngle = $endAngle;
            $endAngle = $tmp;
        }
        
        $this->context->save();
        
        // Draw circular arc path
        $this->context->newPath();
        $this->context->translate(
            $center->x,
            $center->y
        );
        $this->context->scale(
            1, $height / $width
        );

        $this->context->moveTo( 0, 0 );
        $this->context->arc(
            0., 0., 
            $width / 2, 
            deg2rad( $startAngle ),
            deg2rad( $endAngle )
        );
        $this->context->lineTo( 0, 0 );

        $this->context->restore();
        $this->getStyle( $color, $filled );
        $this->context->stroke();

        // Create polygon array to return
        $polygonArray = array( $center );
        for ( $angle = $startAngle; $angle < $endAngle; $angle += $this->options->imageMapResolution )
        {
            $polygonArray[] = new Coordinate(
                $center->x + 
                    ( ( cos( deg2rad( $angle ) ) * $width ) / 2 ),
                $center->y + 
                    ( ( sin( deg2rad( $angle ) ) * $height ) / 2 )
            );
        }
        $polygonArray[] = new Coordinate(
            $center->x + 
                ( ( cos( deg2rad( $endAngle ) ) * $width ) / 2 ),
            $center->y + 
                ( ( sin( deg2rad( $endAngle ) ) * $height ) / 2 )
        );

        return $polygonArray;
    }

    /**
     * Draws a circular arc consisting of several minor steps on the bounding 
     * lines.
     * 
     * @param Coordinate $center 
     * @param mixed $width 
     * @param mixed $height 
     * @param mixed $size 
     * @param mixed $startAngle 
     * @param mixed $endAngle 
     * @param Color $color 
     * @param bool $filled 
     * @return string Element id
     */
    protected function simulateCircularArc( Coordinate $center, $width, $height, $size, $startAngle, $endAngle, Color $color, $filled )
    {
        for ( 
            $tmpAngle = min( ceil ( $startAngle / 180 ) * 180, $endAngle ); 
            $tmpAngle <= $endAngle; 
            $tmpAngle = min( ceil ( $startAngle / 180 + 1 ) * 180, $endAngle ) )
        {
            $this->context->newPath();
            $this->context->moveTo(
                $center->x + cos( deg2rad( $startAngle ) ) * $width / 2, 
                $center->y + sin( deg2rad( $startAngle ) ) * $height / 2
            );

            // @TODO: Use cairo_curve_to()
            for(
                $angle = $startAngle;
                $angle <= $tmpAngle;
                $angle = min( $angle + $this->options->circleResolution, $tmpAngle ) )
            {
                $this->context->lineTo(
                    $center->x + cos( deg2rad( $angle ) ) * $width / 2, 
                    $center->y + sin( deg2rad( $angle ) ) * $height / 2 + $size
                );

                if ( $angle === $tmpAngle )
                {
                    break;
                }
            }

            for(
                $angle = $tmpAngle;
                $angle >= $startAngle;
                $angle = max( $angle - $this->options->circleResolution, $startAngle ) )
            {
                $this->context->lineTo(
                    $center->x + cos( deg2rad( $angle ) ) * $width / 2, 
                    $center->y + sin( deg2rad( $angle ) ) * $height / 2
                );

                if ( $angle === $startAngle )
                {
                    break;
                }
            }

            $this->context->closePath();
            $this->getStyle( $color, $filled );
            $this->context->stroke( );

            $startAngle = $tmpAngle;
            if ( $tmpAngle === $endAngle ) 
            {
                break;
            }
        }
    }

    /**
     * Draws a circular arc
     * 
     * @param Coordinate $center Center of ellipse
     * @param integer $width Width of ellipse
     * @param integer $height Height of ellipse
     * @param integer $size Height of border
     * @param float $startAngle Starting angle of circle sector
     * @param float $endAngle Ending angle of circle sector
     * @param Color $color Color of Border
     * @param bool $filled
     * @return void
     */
    public function drawCircularArc( Coordinate $center, $width, $height, $size, $startAngle, $endAngle, Color $color, $filled = true )
    {
        $this->initiliazeSurface();

        // Normalize angles
        if ( $startAngle > $endAngle )
        {
            $tmp = $startAngle;
            $startAngle = $endAngle;
            $endAngle = $tmp;
        }

        $this->simulateCircularArc( $center, $width, $height, $size, $startAngle, $endAngle, $color, $filled );

        if ( ( $this->options->shadeCircularArc !== false ) &&
             $filled )
        {
            $gradient = new LinearGradient(
                new Coordinate(
                    $center->x - $width,
                    $center->y
                ),
                new Coordinate(
                    $center->x + $width,
                    $center->y
                ),
                Color::fromHex( '#FFFFFF' )->transparent( $this->options->shadeCircularArc * 1.5 ),
                Color::fromHex( '#000000' )->transparent( $this->options->shadeCircularArc * 1.5 )
            );
        
            $this->simulateCircularArc( $center, $width, $height, $size, $startAngle, $endAngle, $gradient, $filled );
        }

        // Create polygon array to return
        $polygonArray = array();
        for ( $angle = $startAngle; $angle < $endAngle; $angle += $this->options->imageMapResolution )
        {
            $polygonArray[] = new Coordinate(
                $center->x + 
                    ( ( cos( deg2rad( $angle ) ) * $width ) / 2 ),
                $center->y + 
                    ( ( sin( deg2rad( $angle ) ) * $height ) / 2 )
            );
        }
        $polygonArray[] = new Coordinate(
            $center->x + 
                ( ( cos( deg2rad( $endAngle ) ) * $width ) / 2 ),
            $center->y + 
                ( ( sin( deg2rad( $endAngle ) ) * $height ) / 2 )
        );

        for ( $angle = $endAngle; $angle > $startAngle; $angle -= $this->options->imageMapResolution )
        {
            $polygonArray[] = new Coordinate(
                $center->x + 
                    ( ( cos( deg2rad( $angle ) ) * $width ) / 2 ) + $size,
                $center->y + 
                    ( ( sin( deg2rad( $angle ) ) * $height ) / 2 )
            );
        }
        $polygonArray[] = new Coordinate(
            $center->x + 
                ( ( cos( deg2rad( $startAngle ) ) * $width ) / 2 ) + $size,
            $center->y + 
                ( ( sin( deg2rad( $startAngle ) ) * $height ) / 2 )
        );

        return $polygonArray;
    }

    /**
     * Draw circle 
     * 
     * @param Coordinate $center Center of ellipse
     * @param mixed $width Width of ellipse
     * @param mixed $height height of ellipse
     * @param Color $color Color
     * @param mixed $filled Filled
     * @return void
     */
    public function drawCircle( Coordinate $center, $width, $height, Color $color, $filled = true )
    {
        $this->initiliazeSurface();
        
        $this->context->save();
        
        // Draw circular arc path
        $this->context->newPath();
        $this->context->translate( 
            $center->x,
            $center->y
        );
        $this->context->scale( 
            1, $height / $width
        );

        $this->context->arc( 
            0., 0., 
            $width / 2, 
            0, 2 * M_PI
        );

        $this->context->restore();
        $this->getStyle( $color, $filled );
        $this->context->stroke();

        // Create polygon array to return
        $polygonArray = array();
        for ( $angle = 0; $angle < ( 2 * M_PI ); $angle += deg2rad( $this->options->imageMapResolution ) )
        {
            $polygonArray[] = new Coordinate(
                $center->x + 
                    ( ( cos( $angle ) * $width ) / 2 ),
                $center->y + 
                    ( ( sin( $angle ) * $height ) / 2 )
            );
        }

        return $polygonArray;
    }

    /**
     * Draw an image 
     *
     * The image will be inlined in the SVG document using data URL scheme. For
     * this the mime type and base64 encoded file content will be merged to 
     * URL.
     * 
     * @param mixed $file Image file
     * @param Coordinate $position Top left position
     * @param mixed $width Width of image in destination image
     * @param mixed $height Height of image in destination image
     * @return void
     */
    public function drawImage( $file, Coordinate $position, $width, $height )
    {
        $this->initiliazeSurface();

        // Ensure given bitmap is a PNG image
        $data = getimagesize( $file );
        if ( $data[2] !== IMAGETYPE_PNG )
        {
            throw new Exception( 'Cairo only has support for PNGs.' );
        }

        // Create new surface from given bitmap
        $imageSurface = CairoImageSurface::createFromPng( $file );

        // Create pattern from source image to be able to transform it
        $pattern = new CairoSurfacePattern( $imageSurface );

        // Scale pattern to defined dimensions and move it to its destination position
        $matrix = CairoMatrix::multiply(
            $move = CairoMatrix::initTranslate( -$position->x, -$position->y ),
            $scale = CairoMatrix::initScale( $data[0] / $width, $data[1] / $height )
        );
        $pattern->setMatrix( $matrix );

        // Merge surfaces
        $this->context->setSource( $pattern );
        $this->context->rectangle( $position->x, $position->y, $width, $height );
        $this->context->fill();
    }

    /**
     * Return mime type for current image format
     * 
     * @return string
     */
    public function getMimeType()
    {
        return 'image/png';
    }

    /**
     * Render image directly to output
     *
     * The method renders the image directly to the standard output. You 
     * normally do not want to use this function, because it makes it harder 
     * to proper cache the generated graphs.
     * 
     * @return void
     */
    public function renderToOutput()
    {
        $this->drawAllTexts();

        header( 'Content-Type: ' . $this->getMimeType() );

        // Write to tmp file, echo and remove tmp file again.
        $fileName = tempnam( '/tmp', 'ezc' );

        // cairo_surface_write_to_png( $this->surface, $file );
        $this->surface->writeToPng( $fileName );
        $contents = file_get_contents( $fileName );
        unlink( $fileName );

        // Directly echo contents
        echo $contents;
    }

    /**
     * Finally save image
     * 
     * @param string $file Destination filename
     * @return void
     */
    public function render( $file )
    {
        $this->drawAllTexts();
        $this->surface->writeToPng( $file );
    }

    /**
     * Get resource of rendered result
     *
     * Return the resource of the rendered result. You should not use this
     * method before you called either renderToOutput() or render(), as the
     * image may not be completely rendered until then.
     *
     * This method returns an array, containing the surface and the context in
     * a structure like:
     * <code>
     *  array(
     *      'surface' => resource,
     *      'context' => resource,
     *  )
     * </code>
     * 
     * @return array
     */
    public function getResource()
    {
        return array( 
            'surface' => $this->surface,
            'context' => $this->context,
        );
    }
}

?>
