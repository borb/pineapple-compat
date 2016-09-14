<?php
class DB_common extends \Mayden\Pineapple\DB\Driver\Common
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
}
