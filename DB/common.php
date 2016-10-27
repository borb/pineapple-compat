<?php
class DB_common extends \Mayden\Pineapple\DB\Driver\Common
{
    // eww, psr-0 class name
    use DB_Components_DeprecatedCommonMethods;

    /**
     * NOTA BENE: this is here because some code refers to DB_common DIRECTLY
     * and without significant hacks we cannot force a pineapple driver to
     * extend this class and not the main-repository Pineapple\DB\Common
     * class.
     *
     * Do NOT move methods here from pineapple, put them into a trait and
     * 'use' them in the driver.
     *
     * this is here ONLY for:
     * - older drivers which extend DB_common
     * - access to static methods in Pineapple\DB\Common
     */
}
