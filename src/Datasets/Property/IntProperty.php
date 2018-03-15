<?php
/**
 * File containing the abstract IntProperty class
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

namespace Ezc\Graph\Datasets\Property;

use Ezc\Graph\Interfaces\AbstractDataSetProperty;

/**
 * Class for integer properties of datasets
 *
 * This class is used to store properties for datasets, which should be
 * validated as integer values.
 *
 * For a basic usage example of those dataset properties take a look at the API
 * documentation of the Ezc\Graph\Interfaces\AbstractDataSetProperty class.
 *
 * @version //autogentag//
 * @package Graph
 */
class IntProperty extends AbstractDataSetProperty
{
    /**
     * Converts value to an {@link \Ezc\Graph\Colors\Color} object
     * 
     * @param & $value 
     * @return void
     */
    protected function checkValue( &$value )
    {
        $value = (int) $value;
        return true;
    }
}

?>