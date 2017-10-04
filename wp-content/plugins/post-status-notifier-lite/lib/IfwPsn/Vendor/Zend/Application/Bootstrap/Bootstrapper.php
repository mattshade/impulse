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
 * @package    IfwPsn_Vendor_Zend_Application
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Bootstrapper.php 1411129 2016-05-05 16:15:58Z worschtebrot $
 */

/**
 * Interface for bootstrap classes
 *
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_Application
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface IfwPsn_Vendor_Zend_Application_Bootstrap_Bootstrapper
{
    /**
     * Constructor
     *
     * @param  IfwPsn_Vendor_Zend_Application $application
     */
    public function __construct($application);

    /**
     * Set bootstrap options
     *
     * @param  array $options
     * @return IfwPsn_Vendor_Zend_Application_Bootstrap_Bootstrapper
     */
    public function setOptions(array $options);

    /**
     * Retrieve application object
     *
     * @return IfwPsn_Vendor_Zend_Application|IfwPsn_Vendor_Zend_Application_Bootstrap_Bootstrapper
     */
    public function getApplication();

    /**
     * Retrieve application environment
     *
     * @return string
     */
    public function getEnvironment();

    /**
     * Retrieve list of class resource initializers (_init* methods). Returns
     * as resource/method pairs.
     *
     * @return array
     */
    public function getClassResources();

    /**
     * Retrieve list of class resource initializer names (resource names only,
     * no method names)
     *
     * @return array
     */
    public function getClassResourceNames();

    /**
     * Bootstrap application or individual resource
     *
     * @param  null|string $resource
     * @return mixed
     */
    public function bootstrap($resource = null);

    /**
     * Run the application
     *
     * @return void
     */
    public function run();
}
