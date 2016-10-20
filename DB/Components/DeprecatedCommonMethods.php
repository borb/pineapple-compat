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
}
