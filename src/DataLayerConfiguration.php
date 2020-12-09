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

class DataLayerConfiguration
{

    public function baseConfiguration() : array
    {
        return [

            'driver' => [

                'mysql' => [
                    'dsn' => 'mysql:host=localhost;dbname=lavacms;charset=utf8mb4',
                    'host' => 'localhost',
                    'database' => 'lavacms',
                    'username' => 'root',
                    'password' => '',
                    'port' => 3306,
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'engine' => 'InnoDB',
                    'row_format' => 'dynamic'
                ],

                'pgsql' => [
                    'dsn' => 'mysql:host=localhost;dbname=lavacms;charset=utf8mb4',
                    'host' => 'lavacms',
                    'database' => 'framework',
                    'username' => 'root',
                    'password' => '',
                    'port' => 3306,
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'engine' => 'InnoDB',
                    'row_format' => 'dynamic'
                ]

            ]

        ];
    }
}