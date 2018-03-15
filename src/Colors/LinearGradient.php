<?php
/**
 * File containing the LinearGradient class
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

namespace Ezc\Graph\Colors;

use Ezc\Graph\Structs\Coordinate;

/**
 * Class representing linear gradient fills. For drivers which cannot draw
 * gradients it falls back to a native {@link Ezc\Graph\Colors\Color}. In this case the
 * start color of the gradient will be used.
 *
 * @property Coordinate $startPoint
 *           Starting point of the gradient.
 * @property Coordinate $endPoint
 *           Ending point of the gradient.
 * @property Color $startColor
 *           Starting color of the gradient.
 * @property Color $endColor
 *           Ending color of the gradient.
 *
 * @version //autogentag//
 * @package Graph
 */
class LinearGradient extends Color
{
    /**
     * Constructor
     * 
     * @param Coordinate $startPoint 
     * @param Coordinate $endPoint 
     * @param Color $startColor 
     * @param Color $endColor 
     * @return void
     */
    public function __construct( Coordinate $startPoint, Coordinate $endPoint, Color $startColor, Color $endColor )
    {
        $this->properties['startColor'] = $startColor;
        $this->properties['endColor'] = $endColor;
        $this->properties['startPoint'] = $startPoint;
        $this->properties['endPoint'] = $endPoint;
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
            case 'startPoint':
                if ( !$propertyValue instanceof Coordinate )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'Coordinate' );
                }
                else
                {
                    $this->properties['startPoint'] = $propertyValue;
                }
                break;
            case 'endPoint':
                if ( !$propertyValue instanceof Coordinate )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'Coordinate' );
                }
                else
                {
                    $this->properties['endPoint'] = $propertyValue;
                }
                break;
            case 'startColor':
                $this->properties['startColor'] = Color::create( $propertyValue );
                break;
            case 'endColor':
                $this->properties['endColor'] = Color::create( $propertyValue );
                break;
        }
    }

    /**
     * __get 
     * 
     * @param mixed $propertyName 
     * @throws ezcBasePropertyNotFoundException
     *          If a the value for the property options is not an instance of
     * @return mixed
     * @ignore
     */
    public function __get( $propertyName )
    {
        switch ( $propertyName )
        {
            case 'red':
            case 'green':
            case 'blue':
            case 'alpha':
                // Fallback to native color
                return $this->properties['startColor']->$propertyName;
            default:
                if ( isset( $this->properties[$propertyName] ) )
                {
                    return $this->properties[$propertyName];
                }
                else
                {
                    throw new ezcBasePropertyNotFoundException( $propertyName );
                }
        }
    }

    /**
     * Returns a unique string representation for the gradient.
     * 
     * @access public
     * @return void
     */
    public function __toString()
    {
        return sprintf( 'LinearGradient_%d_%d_%d_%d_%02x%02x%02x%02x_%02x%02x%02x%02x',
            $this->properties['startPoint']->x,
            $this->properties['startPoint']->y,
            $this->properties['endPoint']->x,
            $this->properties['endPoint']->y,
            $this->properties['startColor']->red,
            $this->properties['startColor']->green,
            $this->properties['startColor']->blue,
            $this->properties['startColor']->alpha,
            $this->properties['endColor']->red,
            $this->properties['endColor']->green,
            $this->properties['endColor']->blue,
            $this->properties['endColor']->alpha
        );
    }
}
?>
