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
use MagmaDataLayer\DataLayerFacade;

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
     * @return void
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $dataRepositoryString) : DataRepositoryInterface
    {
        
        $dataRepositoryObject = new $dataRepositoryString(new DataLayerFacade($this->tableSchema,$this->tableSchemaID));
        if (!$dataRepositoryObject instanceof DataRepositoryInterface) {
            throw new DataLayerUnexpectedValueException($dataRepositoryString . ' is not a valid repository object');
        }
        return $dataRepositoryObject;
    }

}
