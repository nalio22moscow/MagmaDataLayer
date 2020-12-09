<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaDataLayer;

use MagmaDataLayer\DataLayerEnvironment;
use MagmaDataLayer\DataLayerConfiguration;

final class DataLayerFacade
{

    /**
     * Final class which ties the entire data layer togther. The data layer factory
     * is responsible for creating an object of all the component factories and injecting
     * the relevant parameters/arguments. ie the query builder factory, entity manager
     * factory and the data mapper factory.
     * 
     * In order for the data repository to work as a middle man for the client application
     * this facade object is passed in by default to the data repository object which is located
     * within the data repository directory and data repository factory class
     * 
     * @param string $tableSchema
     * @param $tableSchemaID
     * @param null|array $options
     * @return Object - this is effectively making the entity manager which 
     *                  ultimately gives us database access
     *                  through the crud object. and this is the reason why 
     *                  this needs to be passed to the data repository as the 
     *                  object uses the crud methods to build an extra layer for
     *                  easier operation within the client application by exposing 
     *                  methods like find(), findBy(), findOneObject() etc..
     */
    public function __construct(string $tableSchema, string $tableSchemaID, ?array $options = null)
    {
        $dataLayerEnvironment = new DataLayerEnvironment(new DataLayerConfiguration());
        $factory = new DataLayerFactory($dataLayerEnvironment, $tableSchema, $tableSchemaID, $options);
        if ($factory) {
            return $factory->initialize();
        }

    }

}