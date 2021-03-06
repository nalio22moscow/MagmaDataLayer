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

namespace MagmaDataLayer\EntityManager;

use MagmaDataLayer\Exception\DataLayerInvalidArgumentException;
use MagmaDataLayer\Exception\DataLayerUnexpectedValueException;
use MagmaDataLayer\DataMapper\DataMapper;
use MagmaDataLayer\QueryBuilder\QueryBuilder;
use MagmaDataLayer\EntityManager\CrudInterface;
use Throwable;

class Crud implements CrudInterface
{

    /** @var DataMapper */
    protected DataMapper $dataMapper;

    /** @var QueryBuilder */
    protected QueryBuilder $queryBuilder;

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /**
     * Main constructor
     *
     * @param DataMapper $dataMapper
     * @param QueryBuilder $queryBuilder
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchema() : string
    {
        return (string)$this->tableSchema;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchemaID() : string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return integer
     */
    public function lastID() : int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @return boolean
     */
    public function create(array $fields = []) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() ==1) {
                return true;
            }
        } catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array
    {
        //try{
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        if ($this->dataMapper->numRows() >= 0) {
            return $this->dataMapper->results();
        } 
        //} catch(Throwable $throwable) {
            //throw $throwable;
        //}
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param string $primaryKey
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return boolean
     */
    public function delete(array $conditions = []) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     */
    public function search(array $selectors = [], array $conditions = []) : array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->searchQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() >= 0) {
                return $this->dataMapper->results();
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }

    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return Object|null
     */
    public function get(array $selectors = [], array $conditions = []) : ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows() >= 0) {
            return $this->dataMapper->result();
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $sqlQuery
     * @param array|null $conditions
     * @param string $resultType
     * @return void
     */
    public function rawQuery(string $sqlQuery, ?array $conditions = [], string $resultType = 'column')
    {
        $args = ['table' => $this->getSchema(), 'type' => 'raw', 'conditions' => $conditions, 'raw' => $sqlQuery];
        $query = $this->queryBuilder->buildQuery($args)->rawQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows()) {
            if (!in_array($resultType, ['fetch', 'fetch_all', 'column'])) {
                throw new DataLayerInvalidArgumentException('Invalid 3rd argument. Your options are "fetch, fetch_all or column"');
            }
            switch ($resultType) {
                case 'column' :
                    //$data = $this->dataMapper->column(); not implemented yet!
                    break;
                case 'fetch' :
                    $data = $this->dataMapper->result();
                    break;
                case 'fetch_all' :
                    $data = $this->dataMapper->results();
                    break;
                default :
                    throw new DataLayerUnexpectedValueException('Please choose a return type for this method ie. "fetch, fetch_all or column."');
                    break;
            }
            if ($data) {
                return $data;
            }
        }

    }


}
