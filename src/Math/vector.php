<?php
/**
 * File containing the ezcGraphVector class
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

namespace Ezc\Graph\Math;

use Ezc\Graph\Structs\Coordinate;

/**
 * Represents two dimensional vectors
 *
 * This class is internally used to represent vectors for geometric calculation
 * in the two dimensional cartesian coordinate system.
 *
 * Vectors are an extension of the basic coordinate class, and add methods to
 * calculate the length of a vector, perform various operations on angles, like
 * rotations and the calculation of angles between two vectors.
 *
 * @version //autogentag//
 * @package Graph
 * @access private
 */
class ezcGraphVector extends Coordinate
{
    /**
     * Rotates vector to the left by 90 degrees
     * 
     * @return void
     */
    public function rotateCounterClockwise()
    {
        $tmp = $this->x;
        $this->x = $this->y;
        $this->y = -$tmp;

        return $this;
    }

    /**
     * Rotates vector to the right by 90 degrees
     * 
     * @return void
     */
    public function rotateClockwise()
    {
        $tmp = $this->x;
        $this->x = -$this->y;
        $this->y = $tmp;

        return $this;
    }
    
    /**
     * Unifies vector length to 1
     * 
     * @return void
     */
    public function unify()
    {
        $length = $this->length();
        if ( $length == 0 )
        {
            return $this;
        }

        $this->x /= $length;
        $this->y /= $length;

        return $this;
    }

    /**
     * Returns length of vector
     * 
     * @return float
     */
    public function length()
    {
        return sqrt(
            pow( $this->x, 2 ) +
            pow( $this->y, 2 )
        );
    }

    /**
     * Multiplies vector with a scalar
     * 
     * @param float $value 
     * @return void
     */
    public function scalar( $value )
    {
        $this->x *= $value;
        $this->y *= $value;

        return $this;
    }

    /**
     * Calculates scalar product of two vectors
     * 
     * @param Coordinate $vector 
     * @return void
     */
    public function mul( Coordinate $vector )
    {
        return $this->x * $vector->x + $this->y * $vector->y;
    }

    /**
     * Returns the angle between two vectors in radian
     * 
     * @param Coordinate $vector 
     * @return float
     */
    public function angle( Coordinate $vector )
    {
        if ( !$vector instanceof ezcGraphVector )
        {
            // Ensure beeing a vector for calling length()
            $vector = ezcGraphVector::fromCoordinate( $vector );
        }
        
        $factor = $this->length() * $vector->length();

        if ( $factor == 0 )
        {
            return false;
        }
        else
        {
            return acos( $this->mul( $vector ) / $factor );
        }
    }

    /**
     * Adds a vector to another vector
     * 
     * @param Coordinate $vector 
     * @return void
     */
    public function add( Coordinate $vector )
    {
        $this->x += $vector->x;
        $this->y += $vector->y;

        return $this;
    }

    /**
     * Subtracts a vector from another vector
     * 
     * @param Coordinate $vector 
     * @return void
     */
    public function sub( Coordinate $vector )
    {
        $this->x -= $vector->x;
        $this->y -= $vector->y;

        return $this;
    }

    /**
     * Creates a vector from a coordinate object
     * 
     * @param Coordinate $coordinate 
     * @return ezcGraphVector
     */
    public static function fromCoordinate( Coordinate $coordinate )
    {
        return new ezcGraphVector( $coordinate->x, $coordinate->y );
    }

    /**
     * Transform vector using transformation matrix
     * 
     * @param ezcGraphTransformation $transformation 
     * @return ezcGraphVector
     */
    public function transform( ezcGraphTransformation $transformation )
    {
        $result = $transformation->transformCoordinate( $this );

        $this->x = $result->x;
        $this->y = $result->y;

        return $this;
    }
}

?>
