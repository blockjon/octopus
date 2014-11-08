<?php

namespace BlockJon\Tests\Octopus\Functional;

use Daos\BookDao;

class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Given the pdo handle, create a test table.
     *
     * @param \PDO $dbhandle
     * @param array $config
     * @return bool
     */
    protected function createBookTestTableIWithPdoHandle(\PDO $dbhandle, array $config)
    {
        $fieldDefinitions = '';
        foreach ($config['columns'] as $field) {
            $fieldDefinitions .= "`$field` varchar(255), ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions);
        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        $sql = "CREATE TABLE `" . $config['table'] . "` ($fieldDefinitions);";
        $error = $dbhandle->exec($sql);
        return $error == 0;
    }
}