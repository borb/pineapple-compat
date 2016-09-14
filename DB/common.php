<?php
class DB_common extends \Mayden\Pineapple\DB\Driver\Common
{
    // removed in pineapple, deprecated in DB before that
    public function toSleep()
    {
        return $this->__toSleep();
    }
}
