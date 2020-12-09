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

    public function __construct()
    {
        return [

            'default' => 'mysql',

            'driver' => [

                'mysql' => [
                    'dsn' => 'mysql:host=localhost;port=3306;dbname=framework;charset=utf8mb4',
                    'username' => 'root',
                    'password' => ''
                ],

                'pgsql' => [
                    'dsn' => 'pgsql:host=localhost;port=3306;dbname=framework;charset=utf8mb4',
                    'username' => 'root',
                    'password' => ''
                ]

            ]

        ];
    }
}