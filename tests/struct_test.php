<?php
/**
 * ezcGraphStructTest 
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
 * @version //autogen//
 * @subpackage Tests
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Ezc\Graph\Tests;

use Ezc\Graph\Structs\AxisStep;
use Ezc\Graph\Structs\Context;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphStructTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphStructTest" );
	}

    public function testCreateContext()
    {
        $context = new Context( 'set', 'point', 'url://' );

        $this->assertSame(
            'set',
            $context->dataset,
            'Wrong value when reading public property dataset in Context.'
        );

        $this->assertSame(
            'point',
            $context->datapoint,
            'Wrong value when reading public property datapoint in Context.'
        );

        $this->assertSame(
            'url://',
            $context->url,
            'Wrong value when reading public property url in Context.'
        );

        $context->dataset = 'set 2';
        $context->datapoint = 'point 2';
        $context->url = 'url://2';

        $this->assertSame(
            'set 2',
            $context->dataset,
            'Wrong value when reading public property dataset in Context.'
        );

        $this->assertSame(
            'point 2',
            $context->datapoint,
            'Wrong value when reading public property datapoint in Context.'
        );

        $this->assertSame(
            'url://2',
            $context->url,
            'Wrong value when reading public property url in Context.'
        );
    }

    public function testContextUnknowPropertySet()
    {
        $context = new Context( 'set', 'point' );

        try
        {
            $context->unknown = 42;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
    }

    public function testContextUnknowPropertyGet()
    {
        $context = new Context( 'set', 'point' );

        try
        {
            $context->unknown;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
    }

    public function testContextSetState()
    {
        $context = new Context();

        $context->__set_state(
        array(
            'dataset' => 'set',
            'datapoint' => 'point',
        ) );

        $this->assertSame(
            'set',
            $context->dataset,
            'Wrong value when reading public property dataset in Context.'
        );

        $this->assertSame(
            'point',
            $context->datapoint,
            'Wrong value when reading public property datapoint in Context.'
        );
    }

    public function testContextSetStateWithURL()
    {
        $context = new Context();

        $context->__set_state(
        array(
            'dataset' => 'set',
            'datapoint' => 'point',
            'url' => 'url://',
        ) );

        $this->assertSame(
            'set',
            $context->dataset,
            'Wrong value when reading public property dataset in Context.'
        );

        $this->assertSame(
            'point',
            $context->datapoint,
            'Wrong value when reading public property datapoint in Context.'
        );

        $this->assertSame(
            'url://',
            $context->url,
            'Wrong value when reading public property url in Context.'
        );
    }

    public function testCreateCoordinate()
    {
        $context = new Coordinate( 23, 42 );

        $this->assertSame(
            23,
            $context->x,
            'Wrong value when reading public property x in Coordinate.'
        );

        $this->assertSame(
            42,
            $context->y,
            'Wrong value when reading public property y in Coordinate.'
        );

        $context->x = 5;
        $context->y = 12;

        $this->assertSame(
            5,
            $context->x,
            'Wrong value when reading public property x in Coordinate.'
        );

        $this->assertSame(
            12,
            $context->y,
            'Wrong value when reading public property y in Coordinate.'
        );
    }

    public function testCoordinateUnknowPropertySet()
    {
        $context = new Coordinate( 23, 42 );

        try
        {
            $context->unknown = 42;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
    }

    public function testCoordinateUnknowPropertyGet()
    {
        $context = new Coordinate( 23, 42 );

        try
        {
            $context->unknown;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
    }

    public function testCoordinateSetState()
    {
        $context = new Coordinate( 0, 0 );

        $context->__set_state(
        array(
            'x' => 23,
            'y' => 42,
        ) );

        $this->assertSame(
            23,
            $context->x,
            'Wrong value when reading public property x in Coordinate.'
        );

        $this->assertSame(
            42,
            $context->y,
            'Wrong value when reading public property y in Coordinate.'
        );
    }

    public function testCoordinateToString()
    {
        $coordinate = new Coordinate( 2, 5 );

        $this->assertSame(
            '( 2.00, 5.00 )',
            $coordinate->__toString(),
            'Wrong value when converting Coordinate to string.'
        );
    }

    public function testStepSetState()
    {
        $step = new AxisStep();

        $step->__set_state(
        array(
            'position' => .4,
            'width' => .2,
            'label' => 'Label',
            'childs' => array(),
            'isZero' => true,
            'isLast' => false,
        ) );

        $this->assertSame(
            .4,
            $step->position,
            'Wrong value when reading public property position in Context.'
        );

        $this->assertSame(
            .2,
            $step->width,
            'Wrong value when reading public property width in Context.'
        );

        $this->assertSame(
            'Label',
            $step->label,
            'Wrong value when reading public property label in Context.'
        );

        $this->assertSame(
            array(),
            $step->childs,
            'Wrong value when reading public property childs in Context.'
        );

        $this->assertSame(
            true,
            $step->isZero,
            'Wrong value when reading public property isZero in Context.'
        );

        $this->assertSame(
            false,
            $step->isLast,
            'Wrong value when reading public property isLast in Context.'
        );
    }
}
?>
