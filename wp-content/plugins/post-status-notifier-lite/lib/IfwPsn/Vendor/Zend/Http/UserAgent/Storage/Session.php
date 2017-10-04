<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_Http
 * @subpackage UserAgent
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see IfwPsn_Vendor_Zend_Http_UserAgent_Storage
 */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Http/UserAgent/Storage.php';

/**
 * @see IfwPsn_Vendor_Zend_Session_Namespace
 */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Session/Namespace.php';

/**
 * @package    IfwPsn_Vendor_Zend_Http
 * @subpackage UserAgent
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class IfwPsn_Vendor_Zend_Http_UserAgent_Storage_Session implements IfwPsn_Vendor_Zend_Http_UserAgent_Storage
{
    /**
     * Default session namespace
     */
    const NAMESPACE_DEFAULT = 'IfwPsn_Vendor_Zend_Http_UserAgent';

    /**
     * Default session object member name
     */
    const MEMBER_DEFAULT = 'storage';

    /**
     * Object to proxy $_SESSION storage
     *
     * @var IfwPsn_Vendor_Zend_Session_Namespace
     */
    protected $_session;

    /**
     * Session namespace
     *
     * @var mixed
     */
    protected $_namespace;

    /**
     * Session object member
     *
     * @var mixed
     */
    protected $_member;

    /**
     * Sets session storage options and initializes session namespace object
     *
     * Expects options to contain 0 or more of the following keys:
     * - browser_type -- maps to "namespace" internally
     * - member
     *
     * @param  null|array|object $options
     * @return void
     * @throws IfwPsn_Vendor_Zend_Http_UserAgent_Storage_Exception on invalid $options argument
     */
    public function __construct($options = null)
    {
        if (is_object($options) && method_exists($options, 'toArray')) {
            $options = $options->toArray();
        } elseif (is_object($options)) {
            $options = (array) $options;
        }
        if (null !== $options && !is_array($options)) {
            require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Http/UserAgent/Storage/Exception.php';
            throw new IfwPsn_Vendor_Zend_Http_UserAgent_Storage_Exception(sprintf(
                'Expected array or object options; "%s" provided',
                gettype($options)
            ));
        }

        // add '.' to prevent the message ''Session namespace must not start with a number'
        $this->_namespace = '.'
                          . (isset($options['browser_type'])
                             ? $options['browser_type']
                             : self::NAMESPACE_DEFAULT);
        $this->_member    = isset($options['member']) ? $options['member'] : self::MEMBER_DEFAULT;
        $this->_session   = new IfwPsn_Vendor_Zend_Session_Namespace($this->_namespace);
    }

    /**
     * Returns the session namespace name
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Returns the name of the session object member
     *
     * @return string
     */
    public function getMember()
    {
        return $this->_member;
    }

    /**
     * Defined by IfwPsn_Vendor_Zend_Http_UserAgent_Storage
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->_session->{$this->_member});
    }

    /**
     * Defined by IfwPsn_Vendor_Zend_Http_UserAgent_Storage
     *
     * @return mixed
     */
    public function read()
    {
        return $this->_session->{$this->_member};
    }

    /**
     * Defined by IfwPsn_Vendor_Zend_Http_UserAgent_Storage
     *
     * @param  mixed $contents
     * @return void
     */
    public function write($content)
    {
        $this->_session->{$this->_member} = $content;
    }

    /**
     * Defined by IfwPsn_Vendor_Zend_Http_UserAgent_Storage
     *
     * @return void
     */
    public function clear()
    {
        unset($this->_session->{$this->_member});
    }
}
