<?php

use Pineapple\DB;

trait DB_Components_DeprecatedCommonMethods
{
    /**
     * DEPRECATED:  String conversion method
     *
     * @return string  a string describing the current PEAR DB object
     *
     * @deprecated Method deprecated in Release 1.7.0
     */
    public function toSleep()
    {
        return $this->__toSleep();
    }

    /**
     * DEPRECATED: Quotes a string so it can be safely used within string
     * delimiters in a query
     *
     * @param string $string  the string to be quoted
     *
     * @return string  the quoted string
     *
     * @see Common::quoteSmart(), Common::escapeSimple()
     * @deprecated Method deprecated some time before Release 1.2
     */
    public function quoteString($string)
    {
        $string = $this->quoteSmart($string);
        if ($string{0} == "'") {
            return substr($string, 1, -1);
        }
        return $string;
    }

    /**
     * DEPRECATED: Quotes a string so it can be safely used in a query
     *
     * @param string $string  the string to quote
     *
     * @return string  the quoted string or the string <samp>NULL</samp>
     *                  if the value submitted is <kbd>null</kbd>.
     *
     * @see Common::quoteSmart(), Common::escapeSimple()
     * @deprecated Deprecated in release 1.6.0
     */
    public function quote($string = null)
    {
        return $this->quoteSmart($string);
    }

    /**
     * Lists the tables in the current database
     *
     * @return array  the list of tables.  A DB_Error object on failure.
     *
     * @deprecated Method deprecated some time before Release 1.2
     */
    public function getTables()
    {
        return $this->getListOf('tables');
    }

    /**
     * Obtains the query string needed for listing a given type of objects
     *
     * @param string $type  the kind of objects you want to retrieve
     *
     * @return string  the SQL query string or null if the driver doesn't
     *                  support the object type requested
     *
     * @access protected
     * @see Common::getListOf()
     * @deprecated This is deprecated by Pineapple and will be removed in future
     */
    protected function getSpecialQuery($type)
    {
        return $this->raiseError(DB::DB_ERROR_UNSUPPORTED);
    }

    /**
     * Lists internal database information
     *
     * @param string $type  type of information being sought.
     *                      Common items being sought are:
     *                      tables, databases, users, views, functions
     *                      Each DBMS's has its own capabilities.
     *
     * @return array|Error  an array listing the items sought.
     *                      A DB Pineapple\DB\Error object on failure.
     * @deprecated This is deprecated by Pineapple and will be removed in future
     */
    public function getListOf($type)
    {
        $sql = $this->getSpecialQuery($type);
        if ($sql === null) {
            $this->lastQuery = '';
            return $this->raiseError(DB::DB_ERROR_UNSUPPORTED);
        } elseif (is_int($sql) || DB::isError($sql)) {
            // Previous error
            return $this->raiseError($sql);
        } elseif (is_array($sql)) {
            // Already the result
            return $sql;
        }
        // Launch this query
        return $this->getCol($sql);
    }

    /**
     * Creates a new sequence
     *
     * The name of a given sequence is determined by passing the string
     * provided in the <var>$seq_name</var> argument through PHP's sprintf()
     * function using the value from the <var>seqname_format</var> option as
     * the sprintf()'s format argument.
     *
     * <var>seqname_format</var> is set via setOption().
     *
     * @param string $seqName  name of the new sequence
     *
     * @return int|Error       DB_OK on success. A Pineapple\DB\Error object on failure.
     *
     * @see Common::dropSequence(), Common::getSequenceName(),
     *      Common::nextID()
     */
    public function createSequence($seqName)
    {
        return $this->raiseError(DB::DB_ERROR_NOT_CAPABLE);
    }

    /**
     * Deletes a sequence
     *
     * @param string $seqName  name of the sequence to be deleted
     *
     * @return int|Error  DB_OK on success. A Pineapple\DB\Error object on failure.
     *
     * @see Common::createSequence(), Common::getSequenceName(),
     *      Common::nextID()
     */
    public function dropSequence($seqName)
    {
        return $this->raiseError(DB::DB_ERROR_NOT_CAPABLE);
    }

    /**
     * Generates the name used inside the database for a sequence
     *
     * The createSequence() docblock contains notes about storing sequence
     * names.
     *
     * @param string $sqn  the sequence's public name
     *
     * @return string  the sequence's name in the backend
     *
     * @access protected
     * @see Common::createSequence(), Common::dropSequence(),
     *      Common::nextID(), Common::setOption()
     */
    public function getSequenceName($sqn)
    {
        return sprintf($this->getOption('seqname_format'), preg_replace('/[^a-z0-9_.]/i', '_', $sqn));
    }

    /**
     * Returns the next free id in a sequence
     *
     * @param string  $seqName  name of the sequence
     * @param boolean $ondemand when true, the seqence is automatically
     *                          created if it does not exist
     *
     * @return int|Error        the next id number in the sequence.
     *                          A Pineapple\DB\Error object on failure.
     *
     * @see Common::createSequence(), Common::dropSequence(),
     *      Common::getSequenceName()
     */
    public function nextId($seqName, $ondemand = true)
    {
        return $this->raiseError(DB::DB_ERROR_NOT_CAPABLE);
    }
}
