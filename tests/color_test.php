<?php
/**
 * ColorTest 
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

use Ezc\Graph\Colors\Color;
use Ezc\Graph\Colors\LinearGradient;
use Ezc\Graph\Colors\RadialGradient;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ColorTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ColorTest" );
	}

    public function testFactoryColorFromHex()
    {
        $color = Color::fromHex( '#05172A' );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testFactoryColorFromHexWithAlpha()
    {
        $color = Color::fromHex( '#05172A40' );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 64, 'Wrong alpha color value' );
    }

    public function testFactoryColorFromIntegerArray()
    {
        $color = Color::fromIntegerArray( array( 5, 23, 42 ) );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testFactoryColorFromFloatArray()
    {
        $color = Color::fromFloatArray( array( .02, .092, .165 ) );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testFactoryColorCreateFromHex()
    {
        $color = Color::create( '#05172A' );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testFactoryColorCreateFromHexWithAlpha()
    {
        $color = Color::create( '#05172A40' );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 64, 'Wrong alpha color value' );
    }

    public function testFactoryColorCreateFromIntegerArray()
    {
        $color = Color::create( array( 5, 23, 42 ) );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testFactoryColorCreateFromFloatArray()
    {
        $color = Color::create( array( .02, .092, .165 ) );

        $this->assertEquals( $color->red, 5, 'Wrong red color value' );
        $this->assertEquals( $color->green, 23, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 42, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testColorPropertyRed()
    {
        $options = Color::create( '#00000000' );

        $this->assertSame(
            0,
            $options->red,
            'Wrong default value for property red in class Color'
        );

        $options->red = 1;
        $this->assertSame(
            1,
            $options->red,
            'Setting property value did not work for property red in class Color'
        );

        try
        {
            $options->red = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testColorPropertyGreen()
    {
        $options = Color::create( '#00000000' );

        $this->assertSame(
            0,
            $options->green,
            'Wrong default value for property green in class Color'
        );

        $options->green = 1;
        $this->assertSame(
            1,
            $options->green,
            'Setting property value did not work for property green in class Color'
        );

        try
        {
            $options->green = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testColorPropertyBlue()
    {
        $options = Color::create( '#00000000' );

        $this->assertSame(
            0,
            $options->blue,
            'Wrong default value for property blue in class Color'
        );

        $options->blue = 1;
        $this->assertSame(
            1,
            $options->blue,
            'Setting property value did not work for property blue in class Color'
        );

        try
        {
            $options->blue = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testColorPropertyAlpha()
    {
        $options = Color::create( '#00000000' );

        $this->assertSame(
            0,
            $options->alpha,
            'Wrong default value for property alpha in class Color'
        );

        $options->alpha = 1;
        $this->assertSame(
            1,
            $options->alpha,
            'Setting property value did not work for property alpha in class Color'
        );

        try
        {
            $options->alpha = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testColorPropertyNotFoundException()
    {
        try
        {
            $color = Color::create( array( .02, .092, .165 ) );
            $color->black = 23;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
    }

    public function testLinearGradientPropertyNotFoundException()
    {
        $color = new LinearGradient(
            new Coordinate( 0, 0 ),
            new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        try
        {
            $color->black;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }
    }

    public function testLinearGradientPropertyStartPoint()
    {
        $color = new LinearGradient(
            $coord = new Coordinate( 0, 0 ),
            new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            $coord,
            $color->startPoint,
            'Wrong default value for property startPoint in class RadialGradient'
        );

        $color->startPoint = $coord = new Coordinate( 5, 23 );
        $this->assertSame(
            $coord,
            $color->startPoint,
            'Setting property value did not work for property startPoint in class RadialGradient'
        );

        try
        {
            $color->startPoint = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testLinearGradientPropertyEndPoint()
    {
        $color = new LinearGradient(
            new Coordinate( 0, 0 ),
            $coord = new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            $coord,
            $color->endPoint,
            'Wrong default value for property endPoint in class RadialGradient'
        );

        $color->endPoint = $coord = new Coordinate( 5, 23 );
        $this->assertSame(
            $coord,
            $color->endPoint,
            'Setting property value did not work for property endPoint in class RadialGradient'
        );

        try
        {
            $color->endPoint = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testLinearGradientColorFallback()
    {
        $color = new LinearGradient(
            new Coordinate( 0, 0 ),
            new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertEquals( $color->red, 255, 'Wrong red color value' );
        $this->assertEquals( $color->green, 255, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 255, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testRadialGradientColorFallback()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertEquals( $color->red, 255, 'Wrong red color value' );
        $this->assertEquals( $color->green, 255, 'Wrong green color value' );
        $this->assertEquals( $color->blue, 255, 'Wrong blue color value' );
        $this->assertEquals( $color->alpha, 0, 'Wrong alpha color value' );
    }

    public function testLinearGradientProperties()
    {
        $color = new LinearGradient(
            new Coordinate( 0, 0 ),
            new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertEquals( $color->startPoint, new Coordinate( 0, 0 ) );
        $this->assertEquals( $color->endPoint, new Coordinate( 10, 10 ) );
        $this->assertEquals( $color->startColor, Color::fromHex( '#FFFFFF' ) );
        $this->assertEquals( $color->endColor, Color::fromHex( '#00000000' ) );
    }

    public function testRadialGradientPropertyNotFoundException()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        try
        {
            $color->black;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }
    }

    public function testRadialGradientProperties()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertEquals( $color->center, new Coordinate( 0, 0 ) );
        $this->assertEquals( $color->width, 10 );
        $this->assertEquals( $color->height, 20 );
        $this->assertEquals( $color->startColor, Color::fromHex( '#FFFFFF' ) );
        $this->assertEquals( $color->endColor, Color::fromHex( '#00000000' ) );
    }

    public function testRadialGradientPropertyCenter()
    {
        $color = new RadialGradient(
            $coord = new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            $coord,
            $color->center,
            'Wrong default value for property center in class RadialGradient'
        );

        $color->center = $coord = new Coordinate( 5, 23 );
        $this->assertSame(
            $coord,
            $color->center,
            'Setting property value did not work for property center in class RadialGradient'
        );

        try
        {
            $color->center = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadialGradientPropertyWidth()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            10.,
            $color->width,
            'Wrong default value for property width in class RadialGradient'
        );

        $color->width = 20;
        $this->assertSame(
            20.,
            $color->width,
            'Setting property value did not work for property width in class RadialGradient'
        );

        try
        {
            $color->width = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadialGradientPropertyHeight()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            20.,
            $color->height,
            'Wrong default value for property height in class RadialGradient'
        );

        $color->height = 30;
        $this->assertSame(
            30.,
            $color->height,
            'Setting property value did not work for property height in class RadialGradient'
        );

        try
        {
            $color->height = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadialGradientPropertyOffset()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $this->assertSame(
            0,
            $color->offset,
            'Wrong default value for property offset in class RadialGradient'
        );

        $color->offset = .5;
        $this->assertSame(
            .5,
            $color->offset,
            'Setting property value did not work for property offset in class RadialGradient'
        );

        try
        {
            $color->offset = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testLinearGradientSetProperties()
    {
        $color = new LinearGradient(
            new Coordinate( 0, 0 ),
            new Coordinate( 10, 10 ),
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $color->startPoint = new Coordinate( 5, 5 );
        $color->endPoint = new Coordinate( 15, 15 );
        $color->startColor = Color::fromHex( '#000000' );
        $color->endColor = Color::fromHex( '#FFFFFF' );

        $this->assertEquals( $color->startPoint, new Coordinate( 5, 5 ) );
        $this->assertEquals( $color->endPoint, new Coordinate( 15, 15 ) );
        $this->assertEquals( $color->startColor, Color::fromHex( '#000000' ) );
        $this->assertEquals( $color->endColor, Color::fromHex( '#FFFFFF00' ) );
    }

    public function testRadialGradientSetProperties()
    {
        $color = new RadialGradient(
            new Coordinate( 0, 0 ),
            10, 20,
            Color::fromHex( '#FFFFFF' ),
            Color::fromHex( '#000000' )
        );

        $color->center = new Coordinate( 5, 5 );
        $color->width = 15;
        $color->height = 25;
        $color->startColor = Color::fromHex( '#000000' );
        $color->endColor = Color::fromHex( '#FFFFFF' );
        
        $this->assertEquals( $color->center, new Coordinate( 5, 5 ) );
        $this->assertEquals( $color->width, 15 );
        $this->assertEquals( $color->height, 25 );
        $this->assertEquals( $color->startColor, Color::fromHex( '#000000' ) );
        $this->assertEquals( $color->endColor, Color::fromHex( '#FFFFFF00' ) );
    }

    public function testFactoryUnknownColorDefinition()
    {
        try
        {
            $color = Color::create( 1337 );
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException' );
    }

    public function testInvertBlack()
    {
        $color = Color::create( '#000000' )->invert();

        $this->assertEquals(
            $color,
            Color::create( '#FFFFFF' )
        );
    }

    public function testInvertWhite()
    {
        $color = Color::create( '#FFFFFF' )->invert();

        $this->assertEquals(
            $color,
            Color::create( '#000000' )
        );
    }

    public function testInvertTransparentWhite()
    {
        $color = Color::create( '#FFFFFF22' )->invert();

        $this->assertEquals(
            $color,
            Color::create( '#00000022' )
        );
    }

    public function testInvertRandomColor()
    {
        $color = Color::create( '#123456' )->invert();

        $this->assertEquals(
            $color,
            Color::create( '#EDCBA9' )
        );
    }
}
?>
