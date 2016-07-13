<?php
use Mayden\Pineapple\Util as PineappleUtil;

// @todo This should probably be removed, it's really not useful
@ini_set('track_errors', true);

/**
 * ERROR constants
 */
define('PEAR_ERROR_RETURN', PineappleUtil::PEAR_ERROR_RETURN);
define('PEAR_ERROR_PRINT', PineappleUtil::PEAR_ERROR_PRINT);
define('PEAR_ERROR_TRIGGER', PineappleUtil::PEAR_ERROR_TRIGGER);
define('PEAR_ERROR_DIE', PineappleUtil::PEAR_ERROR_DIE);
define('PEAR_ERROR_CALLBACK', PineappleUtil::PEAR_ERROR_CALLBACK);

/**
 * WARNING: obsolete
 * @deprecated
 */
define('PEAR_ERROR_EXCEPTION', PineappleUtil::PEAR_ERROR_EXCEPTION);

class PEAR extends \Mayden\Pineapple\Util
{
    /**
     * Only here for backwards compatibility.
     * E.g. Archive_Tar calls $this->PEAR() in its constructor.
     *
     * @param string $error_class Which class to use for error objects,
     *                            defaults to PEAR_Error.
     */
    public function PEAR($error_class = null)
    {
        self::__construct($error_class);
    }

    /**
     * Destructor (the emulated type of...).  Does nothing right now,
     * but is included for forward compatibility, so subclass
     * destructors should always call it.
     *
     * See the note in the class desciption about output from
     * destructors.
     *
     * @access public
     * @return void
     */
    public function _PEAR() {
        if ($this->_debug) {
            printf("PEAR destructor called, class=%s\n", strtolower(get_class($this)));
        }
    }
}

class PEAR_Error extends \Mayden\Pineapple\Error
{
    /**
     * Only here for backwards compatibility.
     *
     * Class "Cache_Error" still uses it, among others.
     *
     * @param string $message  Message
     * @param int    $code     Error code
     * @param int    $mode     Error mode
     * @param mixed  $options  See __construct()
     * @param string $userinfo Additional user/debug info
     */
    public function PEAR_Error(
        $message = 'unknown error', $code = null, $mode = null,
        $options = null, $userinfo = null
    ) {
        self::__construct($message, $code, $mode, $options, $userinfo);
    }
}

class PEAR_Exception extends \Mayden\Pineapple\Exception
{
}
