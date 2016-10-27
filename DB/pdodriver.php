<?php

use Pineapple\DB;

class DB_pdodriver extends Mayden\Pineapple\DB\Driver\PdoDriver
{
    use DB_Components_DeprecatedCommonMethods;
    use DB_Components_DeprecatedPdoAlikeDriverMethods;
}
