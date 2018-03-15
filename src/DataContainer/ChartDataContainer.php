<?php

namespace Ezc\Graph\DataContainer;

/**
 * File containing the abstract ChartDataContainer class
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

use Ezc\Graph\Datasets\AbstractDataSet;

/**
 * Container class for datasets used by the chart classes. Implements usefull
 * interfaces for convenient access to the datasets.
 *
 * @version //autogentag//
 * @package Graph
 */

class ChartDataContainer implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * Contains the data of a chart
     * 
     * @var array(AbstractDataSet)
     */
    protected $data = array();

    /**
     * Chart using this data set storage
     * 
     * @var ezcGraphChart
     */
    protected $chart;

    /**
     * Constructor
     * 
     * @param ezcGraphChart $chart 
     * @ignore
     * @return void
     */
    public function __construct( ezcGraphChart $chart )
    {
        $this->chart = $chart;
    }

    /**
     * Adds a dataset to the charts data
     * 
     * @param string $name Name of dataset
     * @param AbstractDataSet $dataSet
     * @param mixed $values Values to create dataset with
     * @throws ezcGraphTooManyDataSetExceptions
     *          If too many datasets are created
     * @return AbstractDataSet
     */
    protected function addDataSet( $name, AbstractDataSet $dataSet )
    {
        $this->data[$name] = $dataSet;
        
        $this->data[$name]->label = $name;
        $this->data[$name]->palette = $this->chart->palette;
        $this->data[$name]->displayType = $this->chart->getDefaultDisplayType();
    }

    /**
     * Returns if the given offset exists.
     *
     * This method is part of the ArrayAccess interface to allow access to the
     * data of this object as if it was an array.
     * 
     * @param string $key Identifier of dataset.
     * @return bool True when the offset exists, otherwise false.
     */
    public function offsetExists( $key )
    {
        return isset( $this->data[$key] );
    }

    /**
     * Returns the element with the given offset. 
     *
     * This method is part of the ArrayAccess interface to allow access to the
     * data of this object as if it was an array. 
     * 
     * @param string $key Identifier of dataset.
     * @return AbstractDataSet
     *
     * @throws ezcGraphNoSuchDataSetException
     *         If no dataset with identifier exists
     */
    public function offsetGet( $key )
    {
        if ( !isset( $this->data[$key] ) )
        {
            throw new ezcGraphNoSuchDataSetException( $key );
        }

        return $this->data[$key];
    }

    /**
     * Set the element with the given offset. 
     *
     * This method is part of the ArrayAccess interface to allow access to the
     * data of this object as if it was an array. 
     * 
     * @param string $key
     * @param AbstractDataSet $value
     * @return void
     *
     * @throws ezcBaseValueException
     *         If supplied value is not an AbstractDataSet
     */
    public function offsetSet( $key, $value )
    {
        if ( !$value instanceof AbstractDataSet )
        {
            throw new ezcBaseValueException( $key, $value, 'AbstractDataSet' );
        }

        return $this->addDataSet( $key, $value );
    }

    /**
     * Unset the element with the given offset. 
     *
     * This method is part of the ArrayAccess interface to allow access to the
     * data of this object as if it was an array. 
     * 
     * @param string $key
     * @return void
     */
    public function offsetUnset( $key )
    {
        if ( !isset( $this->data[$key] ) )
        {
            throw new ezcGraphNoSuchDataSetException( $key );
        }

        unset( $this->data[$key] );
    }

    /**
     * Returns the currently selected dataset.
     *
     * This method is part of the Iterator interface to allow access to the 
     * datasets of this row by iterating over it like an array (e.g. using
     * foreach).
     * 
     * @return AbstractDataSet The currently selected dataset.
     */
    public function current()
    {
        return current( $this->data );
    }

    /**
     * Returns the next dataset and selects it or false on the last dataset.
     *
     * This method is part of the Iterator interface to allow access to the 
     * datasets of this row by iterating over it like an array (e.g. using
     * foreach).
     *
     * @return mixed AbstractDataSet if the next dataset exists, or false.
     */
    public function next()
    {
        return next( $this->data );
    }

    /**
     * Returns the key of the currently selected dataset.
     *
     * This method is part of the Iterator interface to allow access to the 
     * datasets of this row by iterating over it like an array (e.g. using
     * foreach).
     * 
     * @return int The key of the currently selected dataset.
     */
    public function key()
    {
        return key( $this->data );
    }

    /**
     * Returns if the current dataset is valid.
     *
     * This method is part of the Iterator interface to allow access to the 
     * datasets of this row by iterating over it like an array (e.g. using
     * foreach).
     *
     * @return bool If the current dataset is valid
     */
    public function valid()
    {
        return ( current( $this->data ) !== false );
    }

    /**
     * Selects the very first dataset and returns it.
     * This method is part of the Iterator interface to allow access to the 
     * datasets of this row by iterating over it like an array (e.g. using
     * foreach).
     *
     * @return AbstractDataSet The very first dataset.
     */
    public function rewind()
    {
        return reset( $this->data );
    }

    /**
     * Returns the number of datasets in the row.
     *
     * This method is part of the Countable interface to allow the usage of
     * PHP's count() function to check how many datasets exist.
     *
     * @return int Number of datasets.
     */
    public function count()
    {
        return count( $this->data );
    }
}
?>
