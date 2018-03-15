<?php
/**
 * File containing the ezcGraphSVGDriver class
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
 * @access private
 */

namespace Ezc\Graph\Driver;

use Ezc\Graph\Options\FontOptions;
use Ezc\Graph\Options\SvgDriverOptions;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Interfaces\AbstractDriver;
use Ezc\Graph\Structs\Coordinate;

/**
 * Simple output driver for debuggin purposes. Just outputs shapes as text on
 * CLI.
 *
 * @version //autogentag//
 * @package Graph
 * @access private
 */

class ezcGraphVerboseDriver extends AbstractDriver
{
    /**
     * Number of call on driver
     * 
     * @var int
     */
    protected $call = 0;

    /**
     * Constructor
     * 
     * @param array $options 
     * @return void
     * @ignore
     */
    public function __construct( array $options = array() )
    {
        $this->options = new SvgDriverOptions( $options );
        echo "\n";
    }

    /**
     * Draws a single polygon 
     * 
     * @param array $points 
     * @param Color $color 
     * @param bool $filled 
     * @param float $thickness
     * @return void
     */
    public function drawPolygon( array $points, Color $color, $filled = true, $thickness = 1. )
    {
        $pointString = '';
        foreach ( $points as $point )
        {
            $pointString .= sprintf( "\t( %.2F, %.2F )\n", $point->x, $point->y );
        }

        printf( "% 4d: Draw %spolygon:\n%s", 
            $this->call++,
            ( $filled ? 'filled ' : '' ),
            $pointString
        );
    }
    
    /**
     * Draws a single line
     * 
     * @param Coordinate $start 
     * @param Coordinate $end 
     * @param Color $color 
     * @param float $thickness
     * @return void
     */
    public function drawLine( Coordinate $start, Coordinate $end, Color $color, $thickness = 1. )
    {
        printf( "% 4d: Draw line from ( %.2F, %.2F ) to ( %.2F, %.2F ) with thickness %d.\n",
            $this->call++,
            $start->x,
            $start->y,
            $end->x,
            $end->y,
            $thickness
        );
    }

    /**
     * Returns boundings of text depending on the available font extension
     * 
     * @param float $size Textsize
     * @param FontOptions $font Font
     * @param string $text Text
     * @return ezcGraphBoundings Boundings of text
     */
    protected function getTextBoundings( $size, FontOptions $font, $text )
    {
        return null;
    }

    /**
     * Wrties text in a box of desired size
     * 
     * @param mixed $string 
     * @param Coordinate $position 
     * @param mixed $width 
     * @param mixed $height 
     * @param int $align 
     * @param ezcGraphRotation $rotation
     * @return void
     */
    public function drawTextBox( $string, Coordinate $position, $width, $height, $align, ezcGraphRotation $rotation = null )
    {
        printf( "% 4d: Draw text '%s' at ( %.2F, %.2F ) with dimensions ( %d, %d ) and alignement %d.\n",
            $this->call++,
            $string,
            $position->x,
            $position->y,
            $width,
            $height,
            $align
        );
    }
    /**
     * Draws a sector of cirlce
     * 
     * @param Coordinate $center 
     * @param mixed $width
     * @param mixed $height
     * @param mixed $startAngle 
     * @param mixed $endAngle 
     * @param Color $color 
     * @param bool $filled
     * @return void
     */
    public function drawCircleSector( Coordinate $center, $width, $height, $startAngle, $endAngle, Color $color, $filled = true )
    {
        printf( "% 4d: Draw %scicle sector at ( %.2F, %.2F ) with dimensions ( %d, %d ) from %.2F to %.2F.\n",
            $this->call++,
            ( $filled ? 'filled ' : '' ),
            $center->x,
            $center->y,
            $width,
            $height,
            $startAngle,
            $endAngle
        );
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
        printf( "% 4d: Draw circular arc at ( %.2F, %.2F ) with dimensions ( %d, %d ) and size %.2F from %.2F to %.2F.\n",
            $this->call++,
            $center->x,
            $center->y,
            $width,
            $height,
            $size,
            $startAngle,
            $endAngle
        );
    }

    /**
     * Draws a circle
     * 
     * @param Coordinate $center 
     * @param mixed $width
     * @param mixed $height
     * @param Color $color
     * @param bool $filled
     *
     * @return void
     */
    public function drawCircle( Coordinate $center, $width, $height, Color $color, $filled = true )
    {
        printf( "% 4d: Draw %scircle at ( %.2F, %.2F ) with dimensions ( %d, %d ).\n",
            $this->call++,
            ( $filled ? 'filled ' : '' ),
            $center->x,
            $center->y,
            $width,
            $height
        );
    }

    /**
     * Draws a imagemap of desired size
     * 
     * @param mixed $file 
     * @param Coordinate $position 
     * @param mixed $width 
     * @param mixed $height 
     * @return void
     */
    public function drawImage( $file, Coordinate $position, $width, $height )
    {
        printf( "% 4d: Draw image '%s' at ( %.2F, %.2F ) with dimensions ( %d, %d ).\n",
            $this->call++,
            $file,
            $position->x,
            $position->y,
            $width,
            $height
        );
    }

    /**
     * Return mime type for current image format
     * 
     * @return string
     */
    public function getMimeType()
    {
        return 'text/plain';
    }

    /**
     * Finally save image
     * 
     * @param mixed $file 
     * @return void
     */
    public function render ( $file )
    {
        printf( "Render image.\n" );
    }
}

?>
