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

namespace MagmaDataLayer\DataRepository;

use MagmaDataLayer\Exception\DataLayerUnexpectedValueException;
use MagmaDataLayer\DataRepository\DataRepositoryInterface;
use MagmaDataLayer\DataLayerEnvironment;
use MagmaDataLayer\DataLayerConfiguration;
use MagmaDataLayer\DataLayerFactory;

class DataRepositoryFactory
{

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var string */
    protected string $crudIdentifier;

    /**
     * Main class constructor
     *
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Create the DataRepository Object. Which is the middle layer that interacts with
     * the application using this framework. The data repository object will have 
     * the required dependency injected by default. Which is the data layer facade object
     * which is simple passing in the entity manager object which expose the crud methods
     *
     * @param string $dataRepositoryString
     * @param array|null $dataLayerConfiguration
     * @return DataRepositoryInterface
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $dataRepositoryString, ?array $dataLayerConfiguration = null) : DataRepositoryInterface
    {
        
        $dataRepositoryObject = new $dataRepositoryString($this->entityBuilder($dataLayerConfiguration));
        if (!$dataRepositoryObject instanceof DataRepositoryInterface) {
            throw new DataLayerUnexpectedValueException($dataRepositoryString . ' is not a valid repository object');
        }
        return $dataRepositoryObject;
    }

    /**
     * Undocumented function
     *
     * @param array|null $dataLayerConfiguration
     * @return Object
     */
    public function entityBuilder(?array $dataLayerConfiguration = null) : Object
    {
        $dataLayerEnvironment = new DataLayerEnvironment(new DataLayerConfiguration(($dataLayerConfiguration !=null) ? $dataLayerConfiguration : (new DataLayerConfiguration())->baseConfiguration()));
        
        $factory = new DataLayerFactory($dataLayerEnvironment, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            return $factory->build();
        }

    }


}
