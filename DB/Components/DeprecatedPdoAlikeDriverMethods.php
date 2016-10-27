<?php

use Pineapple\DB;

trait DB_Components_DeprecatedSequenceMethods
{
    /**
     * Returns the next free id in a sequence
     *
     * @param string  $seqName  name of the sequence
     * @param boolean $onDemand when true, the seqence is automatically
     *                          created if it does not exist
     *
     * @return int|Error  the next id number in the sequence.
     *                    A Pineapple\DB\Error object on failure.
     *
     * @see Pineapple\DB\Driver\Common::nextID(),
     *      Pineapple\DB\Driver\Common::getSequenceName(),
     *      Pineapple\DB\Driver\PdoDriver::createSequence(),
     *      Pineapple\DB\Driver\PdoDriver::dropSequence()
     *
     * @deprecated Pineapple retains but does not support this, it's restricted to mysql, and untested
     * @codeCoverageIgnore
     */
    public function nextId($seqName, $onDemand = true)
    {
        /**
         * this method is in here for LEGACY purposes only, and because it does not
         * easily fit into pineapple-compat. only support mysql platform, error if
         * other platform detected.
         */
        if ($this->getPlatform() !== 'mysql') {
            return $this->raiseError(DB::DB_ERROR_UNSUPPORTED);
        }

        $seqName = $this->getSequenceName($seqName);
        do {
            $repeat = 0;
            $result = $this->query("UPDATE {$seqName} SET id = LAST_INSERT_ID(id + 1)");
            if ($result === DB::DB_OK) {
                // COMMON CASE
                $id = $this->connection->lastInsertId();
                if ($id != 0) {
                    return $id;
                }

                // EMPTY SEQ TABLE
                // Sequence table must be empty for some reason,
                // so fill it and return 1
                // Obtain a user-level lock
                $result = $this->getOne("SELECT GET_LOCK('${seqName}_lock', 10)");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                if ($result == 0) {
                    return $this->myRaiseError(DB::DB_ERROR_NOT_LOCKED);
                }

                // add the default value
                $result = $this->query("REPLACE INTO {$seqName} (id) VALUES (0)");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }

                // Release the lock
                $result = $this->getOne("SELECT RELEASE_LOCK('${seqName}_lock')");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                // We know what the result will be, so no need to try again
                return 1;
            } elseif ($onDemand && DB::isError($result) &&
                $result->getCode() == DB::DB_ERROR_NOSUCHTABLE) {
                // ONDEMAND TABLE CREATION
                $result = $this->createSequence($seqName);

                // Since createSequence initializes the ID to be 1,
                // we do not need to retrieve the ID again (or we will get 2)
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }

                // First ID of a newly created sequence is 1
                return 1;
            }
        } while ($repeat);

        return $this->raiseError($result);
    }

    /**
     * Creates a new sequence
     *
     * @param string $seqName  name of the new sequence
     *
     * @return int|Error       DB_OK on success. A Pineapple\DB\Error
     *                         object on failure.
     *
     * @see Pineapple\DB\Driver\Common::createSequence(),
     *      Pineapple\DB\Driver\Common::getSequenceName(),
     *      Pineapple\DB\Driver\Pineapple\DB\Driver\PdoDriver::nextID(),
     *      Pineapple\DB\Driver\Pineapple\DB\Driver\PdoDriver::dropSequence()
     *
     * @deprecated Pineapple retains but does not support this, it's restricted to mysql, and untested
     * @codeCoverageIgnore
     */
    public function createSequence($seqName)
    {
        /**
         * this method is in here for LEGACY purposes only, and because it does not
         * easily fit into pineapple-compat. only support mysql platform, error if
         * other platform detected.
         */
        if ($this->getPlatform() !== 'mysql') {
            return $this->raiseError(DB::DB_ERROR_UNSUPPORTED);
        }

        $seqName = $this->getSequenceName($seqName);
        $res = $this->query("CREATE TABLE {$seqName} (id INTEGER UNSIGNED AUTO_INCREMENT NOT NULL, PRIMARY KEY(id))");
        if (DB::isError($res)) {
            return $res;
        }
        // insert yields value 1, nextId call will generate ID 2
        return $this->query("INSERT INTO ${seqName} (id) VALUES (0)");
    }

    /**
     * Deletes a sequence
     *
     * @param string $seqName  name of the sequence to be deleted
     *
     * @return int|Error       DB_OK on success. A Pineapple\DB\Error object
     *                         on failure.
     *
     * @see Pineapple\DB\Driver\Common::dropSequence(),
     *      Pineapple\DB\Driver\Common::getSequenceName(),
     *      Pineapple\DB\Driver\Pineapple\DB\Driver\PdoDriver::nextID(),
     *      Pineapple\DB\Driver\Pineapple\DB\Driver\PdoDriver::createSequence()
     *
     * @deprecated Pineapple retains but does not support this, it's restricted to mysql, and untested
     * @codeCoverageIgnore
     */
    public function dropSequence($seqName)
    {
        /**
         * this method is in here for LEGACY purposes only, and because it does not
         * easily fit into pineapple-compat. only support mysql platform, error if
         * other platform detected.
         */
        if ($this->getPlatform() !== 'mysql') {
            return $this->raiseError(DB::DB_ERROR_UNSUPPORTED);
        }

        return $this->query('DROP TABLE ' . $this->getSequenceName($seqName));
    }

    /**
     * Obtains the query string needed for listing a given type of objects
     *
     * @param string $type  the kind of objects you want to retrieve
     *
     * @return string|null  the SQL query string or null if the driver
     *                      doesn't support the object type requested
     *
     * @access protected
     * @deprecated This is deprecated by Pineapple and will be removed in future
     * @see Pineapple\DB\Driver\Common::getListOf()
     * @codeCoverageIgnore
     */
    protected function getSpecialQuery($type)
    {
        if ($this->getPlatform() !== 'mysql') {
            return $this->myRaiseError(DB::DB_ERROR_UNSUPPORTED);
        }

        switch ($type) {
            case 'tables':
                return 'SHOW TABLES';
            case 'users':
                return 'SELECT DISTINCT User FROM mysql.user';
            case 'databases':
                return 'SHOW DATABASES';
            default:
                return null;
        }
    }
}
