<?php
/**
 * ezcGraphGdDriverTest 
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


use Ezc\Graph\Options\GdDriverOptions;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphGdDriverTest extends ezcTestImageCase
{
    protected $driver;

    protected $tempDir;

    protected $basePath;

    protected $testFiles = array(
        'jpeg'          => 'jpeg.jpg',
        'png'           => 'png.png',
        'gif'           => 'gif.gif',
    );

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphGdDriverTest" );
	}

    public function setUp()
    {
        parent::setUp();

        if ( !BaseFeatures::hasExtensionSupport( 'gd' ) && 
             ( BaseFeatures::hasFunction( 'imagefttext' ) || BaseFeatures::hasFunction( 'imagettftext' ) ) )
        {
            $this->markTestSkipped( 'This test needs ext/gd with native ttf support or FreeType 2 support.' );
        }

        static $i = 0;
        $this->tempDir = $this->createTempDir( __CLASS__ . sprintf( '_%03d_', ++$i ) ) . '/';
        $this->basePath = dirname( __FILE__ ) . '/data/';

        $this->driver = new ezcGraphGdDriver();
        $this->driver->options->width = 200;
        $this->driver->options->height = 100;
        $this->driver->options->font->path = $this->basePath . 'font.ttf';
        $this->driver->options->supersampling = 1;
    }

    public function tearDown()
    {
        unset( $this->driver );
        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }

    public function testRenderPngToOutput()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->imageFormat = IMG_PNG;
        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        $this->assertEquals(
            $this->driver->getMimeType(),
            'image/png',
            'Wrong mime type returned.'
        );

        ob_start();
        // Suppress header already sent warning
        @$this->driver->renderToOutput();
        file_put_contents( $filename, ob_get_clean() );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testGetResource()
    {
        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        ob_start();
        // Suppress header already sent warning
        @$this->driver->renderToOutput();
        ob_end_clean();

        $resource = $this->driver->getResource();
        $this->assertEquals(
            'resource',
            gettype( $resource )
        );
    }

    public function testRenderJpegToOutput() {
        $filename = $this->tempDir . __FUNCTION__ . '.jpeg';

        $this->driver->options->imageFormat = IMG_JPEG;
        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        $this->assertEquals(
            $this->driver->getMimeType(),
            'image/jpeg',
            'Wrong mime type returned.'
        );

        ob_start();
        // Suppress header already sent warning
        @$this->driver->renderToOutput();
        file_put_contents( $filename, ob_get_clean() );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.jpeg',
            'Image does not look as expected.',
            2000
        );
    }

    public function testRenderUnhandledFormat() {
        $filename = $this->tempDir . __FUNCTION__ . '.jpeg';

        $this->driver->options->imageFormat = IMG_GIF;
        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        try
        {
            $filename = $this->tempDir . __FUNCTION__ . '.jpeg';
            $this->driver->render( $filename );
        }
        catch ( ezcGraphGdDriverUnsupportedImageTypeException $e )
        {
            return;
        }

        $this->fail( 'Expected ezcGraphGdDriverUnsupportedImageTypeException.' );
    }

    public function testDrawLine()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawLineWithDifferentWidths()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' ),
            3
        );

        $this->driver->drawLine(
            new Coordinate( 12, 35 ),
            new Coordinate( 134, 2 ),
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawPolygonThreePointsFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $return = $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A4' ),
            true
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            'Expected point array as return value.'
        );
    }

    public function testDrawTransparentPolygon()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A47F' ),
            true
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawPolygonThreePointsNotFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawPolygonFivePoints()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
                new Coordinate( 3, 45 ),
                new Coordinate( 60, 32 ),
            ),
            Color::fromHex( '#3465A4' ),
            true
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircleSectorAcute()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $return = $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            12.5,
            25,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(
                new Coordinate( 100., 50. ),
                new Coordinate( 139., 54. ),
                new Coordinate( 137., 58. ),
                new Coordinate( 136., 58.5 ),
            ),
            'Expected point array as return value.',
            1.
        );
    }

    public function testDrawCircleSectorAcuteVerySmallBug13361()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $return = $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            30,
            30.1,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(),
            'Expected empty point array as return value.',
            1.
        );
    }

    public function testDrawFullCircleSectorBug14655()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        // Draw filled cicle sector at ( 240.00, 95.00 ) with dimensions ( 144, 76 ) from 0.00 to 360.00.
        $return = $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            0.,
            360.,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircleSectorAcuteNonFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            12.5,
            45,
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircleSectorAcuteReverse()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            25,
            12.5,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircleSectorObtuse()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            25,
            273,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
        $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircularArcAcute()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->imageMapResolution = 90;
        $return = $this->driver->drawCircularArc(
            new Coordinate( 100, 50 ),
            150,
            80,
            10,
            12.5,
            55,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(
                new Coordinate( 173., 59. ),
                new Coordinate( 143., 83. ),
                new Coordinate( 153., 83. ),
                new Coordinate( 183., 59. ),
            ),
            'Expected point array as return value.',
            1.
        );
    }

    public function testDrawCircularArcAcuteReverse()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircularArc(
            new Coordinate( 100, 50 ),
            150,
            80,
            10,
            55,
            12.5,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircularArcObtuse()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircularArc(
            new Coordinate( 100, 50 ),
            150,
            70,
            10,
            25,
            300,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawCircularArcAcuteBorder()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircularArc(
            new Coordinate( 100, 50 ),
            150,
            80,
            10,
            12.5,
            55,
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            0
        );
    }

    public function testDrawCircleFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->imageMapResolution = 90;
        $return = $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(
                new Coordinate( 140., 50. ),
                new Coordinate( 100., 70. ),
                new Coordinate( 60., 50. ),
                new Coordinate( 100., 30. ),
            ),
            'Expected point array as return value.',
            1.
        );
    }

    public function testDrawCircleNonFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawImageJpeg()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $return = $this->driver->drawImage(
            $this->basePath . $this->testFiles['jpeg'],
            new Coordinate( 10, 10 ),
            100,
            50
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(
                new Coordinate( 10., 10. ),
                new Coordinate( 110., 10. ),
                new Coordinate( 110., 60. ),
                new Coordinate( 10., 60. ),
            ),
            'Expected point array as return value.',
            1.
        );
    }

    public function testDrawImagePng()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawImage(
            $this->basePath . $this->testFiles['png'],
            new Coordinate( 10, 10 ),
            100,
            50
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawImageGif()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawImage(
            $this->basePath . $this->testFiles['gif'],
            new Coordinate( 10, 10 ),
            100,
            50
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortString()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );

        $this->assertEquals(
            $return,
            array(
                new Coordinate( 10., 10. ),
                new Coordinate( 160., 10. ),
                new Coordinate( 160., 80. ),
                new Coordinate( 10., 80. ),
            ),
            'Expected point array as return value.',
            1.
        );
    }

    public function testDrawTextBoxShortStringRotated10Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 10 )
        );

        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortPSStringRotated10Degrees()
    {
        if ( !BaseFeatures::hasFunction( 'imagepstext' ) )
        {
            $this->markTestSkipped( 'This test needs Type 1 font support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 10 )
        );

        $this->driver->options->font->path = $this->basePath . 'ps_font.pfb';
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortNativeTTFStringRotated10Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 10 )
        );

        $this->driver->options->forceNativeTTF = true;
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortStringRotated45Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 45, new Coordinate( 100, 50 ) )
        );

        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortPSStringRotated45Degrees()
    {
        if ( !BaseFeatures::hasFunction( 'imagepstext' ) )
        {
            $this->markTestSkipped( 'This test needs Type 1 font support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 45, new Coordinate( 100, 50 ) )
        );

        $this->driver->options->font->path = $this->basePath . 'ps_font.pfb';
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortNativeTTFStringRotated45Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 45, new Coordinate( 100, 50 ) )
        );

        $this->driver->options->forceNativeTTF = true;
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortStringRotated340Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 340, new Coordinate( 200, 100 ) )
        );

        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortPSStringRotated340Degrees()
    {
        if ( !BaseFeatures::hasFunction( 'imagepstext' ) )
        {
            $this->markTestSkipped( 'This test needs Type 1 font support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 340, new Coordinate( 200, 100 ) )
        );

        $this->driver->options->font->path = $this->basePath . 'ps_font.pfb';
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxShortNativeTTFStringRotated340Degrees()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        
        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $return = $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT,
            new ezcGraphRotation( 340, new Coordinate( 200, 100 ) )
        );

        $this->driver->options->forceNativeTTF = true;
        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongString()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'ThisIsAPrettyLongString',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongSpacedString()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'This Is A Pretty Long String',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxManualBreak()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            "New\nLine",
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxStringSampleRight()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 20, 20 ),
                new Coordinate( 110, 20 ),
                new Coordinate( 110, 30 ),
                new Coordinate( 20, 30 ),
            ),
            Color::fromHex( '#eeeeec' ),
            true
        );
        $this->driver->drawTextBox(
            'sample 4',
            new Coordinate( 21, 21 ),
            88,
            8,
            ezcGraph::RIGHT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxStringRight()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'ThisIsAPrettyLongString',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::RIGHT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongSpacedStringRight()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'This Is A Pretty Long String',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::RIGHT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxStringCenter()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'ThisIsAPrettyLongString',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::CENTER
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongSpacedStringCenter()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'This Is A Pretty Long String',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::CENTER
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxStringRightBottom()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'ThisIsAPrettyLongString',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::RIGHT | ezcGraph::BOTTOM
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongSpacedStringRightMiddle()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'This Is A Pretty Long String',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::RIGHT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxStringCenterMiddle()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'ThisIsAPrettyLongString',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::CENTER | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextBoxLongSpacedStringCenterBottom()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'This Is A Pretty Long String',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::CENTER | ezcGraph::BOTTOM
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawLine()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testHighSupersamplingDrawLine()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 3;

        $this->driver->drawLine(
            new Coordinate( 12, 45 ),
            new Coordinate( 134, 12 ),
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawPolygonThreePointsFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A4' ),
            true
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawPolygonThreePointsNotFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawCircleSectorAcute()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawCircleSector(
            new Coordinate( 100, 50 ),
            80,
            40,
            12.5,
            25,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawCircularArcAcute()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawCircularArc(
            new Coordinate( 100, 50 ),
            150,
            80,
            10,
            12.5,
            55,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawCircleFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A4' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawCircleNonFilled()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A4' ),
            false
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawImagePng()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawImage(
            $this->basePath . $this->testFiles['png'],
            new Coordinate( 10, 10 ),
            100,
            50
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawImagePngWithBackground()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;
        $this->driver->options->background = $this->basePath . $this->testFiles['png'];

        $this->driver->drawImage(
            $this->basePath . $this->testFiles['jpeg'],
            new Coordinate( 10, 10 ),
            100,
            50
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawTransparentCircleFilledWithBackground()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;
        $this->driver->options->background = $this->basePath . $this->testFiles['png'];

        $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A47F' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testRisizedSupersamplingDrawTransparentCircleFilledWithBackground()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;
        $this->driver->options->background = $this->basePath . $this->testFiles['png'];
        $this->driver->options->resampleFunction = 'imagecopyresized';

        $this->driver->drawCircle(
            new Coordinate( 100, 50 ),
            80,
            40,
            Color::fromHex( '#3465A47F' )
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testSupersamplingDrawTextBoxShortString()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->supersampling = 2;

        $this->driver->drawTextBox(
            'Short',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawSmallNativeTTFStringWithSpecialChars()
    {
        if ( !BaseFeatures::hasFunction( 'imagettftext' ) )
        {
            $this->markTestSkipped( 'This test needs native ttf support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->forceNativeTTF = true;

        $this->driver->drawPolygon(
            array(
                new Coordinate( 47, 54 ),
                new Coordinate( 47, 84 ),
                new Coordinate( 99, 84 ),
                new Coordinate( 99, 54 ),
            ),
            Color::fromHex( '#DDDDDD' ),
            true
        );
        $this->driver->drawTextBox(
            'Safari (13.8%)',
            new Coordinate( 47, 54 ),
            52,
            30,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            10
        );
    }

    public function testDrawSmallFreeTypeStringWithSpecialChars()
    {
        if ( !BaseFeatures::hasFunction( 'imagefttext' ) )
        {
            $this->markTestSkipped( 'This test needs FreeType 2 ttf support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawPolygon(
            array(
                new Coordinate( 47, 54 ),
                new Coordinate( 47, 84 ),
                new Coordinate( 99, 84 ),
                new Coordinate( 99, 54 ),
            ),
            Color::fromHex( '#DDDDDD' ),
            true
        );
        $this->driver->drawTextBox(
            'Safari (13.8%)',
            new Coordinate( 47, 54 ),
            52,
            30,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            10
        );
    }

    public function testDrawSmallPsStringWithSpecialChars()
    {
        if ( !BaseFeatures::hasFunction( 'imagepstext' ) )
        {
            $this->markTestSkipped( 'This test needs Type 1 font support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->font->path = $this->basePath . 'ps_font.pfb';

        $this->driver->drawPolygon(
            array(
                new Coordinate( 47, 54 ),
                new Coordinate( 47, 84 ),
                new Coordinate( 99, 84 ),
                new Coordinate( 99, 54 ),
            ),
            Color::fromHex( '#DDDDDD' ),
            true
        );
        $this->driver->drawTextBox(
            'Safari (13.8%)',
            new Coordinate( 47, 54 ),
            52,
            30,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            10
        );
    }

    public function testDrawNativeTTFText()
    {
        if ( !BaseFeatures::hasFunction( 'imagettftext' ) )
        {
            $this->markTestSkipped( 'This test needs native ttf support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->font->path = $this->basePath . 'font.ttf';
        $this->driver->options->forceNativeTTF = true;

        $this->driver->drawTextBox(
            'Fontfiletest',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawFreeTypeTTFText()
    {
        if ( !BaseFeatures::hasFunction( 'imagefttext' ) )
        {
            $this->markTestSkipped( 'This test needs FreeType 2 ttf support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->font->path = $this->basePath . 'font.ttf';

        $this->driver->drawTextBox(
            'Fontfiletest',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawPSText()
    {
        if ( !BaseFeatures::hasFunction( 'imagepstext' ) )
        {
            $this->markTestSkipped( 'This test needs Type 1 font support within your gd extension.' );
        }

        $filename = $this->tempDir . __FUNCTION__ . '.png';
        $this->driver->options->font->path = $this->basePath . 'ps_font.pfb';

        $this->driver->drawTextBox(
            'Fontfiletest',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTooLongTextException()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        try
        {
            $this->driver->drawTextBox(
                'This is very long text which is not supposed to fit in the bounding box.',
                new Coordinate( 10, 10 ),
                1,
                20,
                ezcGraph::LEFT
            );

            $this->driver->render( $filename );
        }
        catch ( ezcGraphFontRenderingException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphFontRenderingException.' );
    }

    public function testDrawTextWithTextShadow()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->textShadow = true;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithCustomTextShadow()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->textShadow = true;
        $this->driver->options->font->textShadowColor = '#888888';

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithBackground()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = false;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithBackgroundAndSupersampling()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->supersampling = 2;
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = false;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithBorder()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->minimizeBorder = false;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithMinimizedBorderAndBackgroundTopLeft()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::TOP
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawRotatedTextWithMinimizedBorderAndBackgroundTopLeft()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::LEFT | ezcGraph::TOP,
            new ezcGraphRotation( 15 )
        );

        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithMinimizedBorderAndBackgroundMiddleCenter()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::CENTER | ezcGraph::MIDDLE
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawTextWithMinimizedBorderAndBackgroundBottomRight()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->options->font->border = Color::fromHex( '#555555' );
        $this->driver->options->font->background = Color::fromHex( '#DDDDDD' );
        $this->driver->options->font->minimizeBorder = true;
        $this->driver->options->font->padding = 2;

        $this->driver->drawTextBox(
            'Some test string',
            new Coordinate( 10, 10 ),
            150,
            70,
            ezcGraph::RIGHT | ezcGraph::BOTTOM
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testShortenStringMoreChars()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.png';

        $this->driver->drawTextBox(
            'Teststring foo',
            new Coordinate( 10, 10 ),
            24,
            6,
            ezcGraph::LEFT
        );

        $this->driver->render( $filename );

        $this->assertImageSimilar( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.png',
            'Image does not look as expected.',
            2000
        );
    }

    public function testDrawJpeg()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.jpg';

        $this->driver->options->imageFormat = IMG_JPEG;
        $this->driver->drawPolygon(
            array( 
                new Coordinate( 45, 12 ),
                new Coordinate( 122, 34 ),
                new Coordinate( 12, 71 ),
            ),
            Color::fromHex( '#3465A4' ),
            true
        );

        $this->driver->render( $filename );

        $this->assertTrue(
            file_exists( $filename ),
            'No image was generated.'
        );

        $this->assertImageSimilar(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.jpg',
            'Image does not look as expected.',
            2000
        );
    }

    public function testGdDriverOptionsPropertyImageFormat()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            IMG_PNG,
            $options->imageFormat,
            'Wrong default value for property imageFormat in class GdDriverOptions'
        );

        $options->imageFormat = IMG_JPEG;
        $this->assertSame(
            IMG_JPEG,
            $options->imageFormat,
            'Setting property value did not work for property imageFormat in class GdDriverOptions'
        );

        try
        {
            $options->imageFormat = false;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyJpegQuality()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            70,
            $options->jpegQuality,
            'Wrong default value for property jpegQuality in class GdDriverOptions'
        );

        $options->jpegQuality = 100;
        $this->assertSame(
            100,
            $options->jpegQuality,
            'Setting property value did not work for property jpegQuality in class GdDriverOptions'
        );

        try
        {
            $options->jpegQuality = false;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyDetail()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            1,
            $options->detail,
            'Wrong default value for property detail in class GdDriverOptions'
        );

        $options->detail = 5;
        $this->assertSame(
            5,
            $options->detail,
            'Setting property value did not work for property detail in class GdDriverOptions'
        );

        try
        {
            $options->detail = false;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertySupersampling()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            2,
            $options->supersampling,
            'Wrong default value for property supersampling in class GdDriverOptions'
        );

        $options->supersampling = 4;
        $this->assertSame(
            4,
            $options->supersampling,
            'Setting property value did not work for property supersampling in class GdDriverOptions'
        );

        try
        {
            $options->supersampling = false;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyBackground()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            false,
            $options->background,
            'Wrong default value for property background in class GdDriverOptions'
        );

        $options->background = $file = dirname( __FILE__ ) . '/data/jpeg.jpg';
        $this->assertSame(
            $file,
            $options->background,
            'Setting property value did not work for property background in class GdDriverOptions'
        );

        try
        {
            $options->background = 'foo';
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyResampleFunction()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            'imagecopyresampled',
            $options->resampleFunction,
            'Wrong default value for property resampleFunction in class GdDriverOptions'
        );

        $options->resampleFunction = 'imagecopyresized';
        $this->assertSame(
            'imagecopyresized',
            $options->resampleFunction,
            'Setting property value did not work for property resampleFunction in class GdDriverOptions'
        );

        try
        {
            $options->resampleFunction = 'foo';
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyForceNativeTTF()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            false,
            $options->forceNativeTTF,
            'Wrong default value for property forceNativeTTF in class GdDriverOptions'
        );

        $options->forceNativeTTF = true;
        $this->assertSame(
            true,
            $options->forceNativeTTF,
            'Setting property value did not work for property forceNativeTTF in class GdDriverOptions'
        );

        try
        {
            $options->forceNativeTTF = 42;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }

    public function testGdDriverOptionsPropertyImageMapResolution()
    {
        $options = new GdDriverOptions();

        $this->assertSame(
            10,
            $options->imageMapResolution,
            'Wrong default value for property imageMapResolution in class GdDriverOptions'
        );

        $options->imageMapResolution = 5;
        $this->assertSame(
            5,
            $options->imageMapResolution,
            'Setting property value did not work for property imageMapResolution in class GdDriverOptions'
        );

        try
        {
            $options->imageMapResolution = false;
        }
        catch ( BaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected BaseValueException.' );
    }
}
?>
