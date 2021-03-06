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
 * @package    IfwPsn_Vendor_Zend_Validate
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Identcode.php 1411129 2016-05-05 16:15:58Z worschtebrot $
 */

/**
 * @see IfwPsn_Vendor_Zend_Validate_Barcode_AdapterAbstract
 */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Validate/Barcode/AdapterAbstract.php';

/**
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_Validate
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class IfwPsn_Vendor_Zend_Validate_Barcode_Identcode extends IfwPsn_Vendor_Zend_Validate_Barcode_AdapterAbstract
{
    /**
     * Allowed barcode lengths
     * @var integer
     */
    protected $_length = 12;

    /**
     * Allowed barcode characters
     * @var string
     */
    protected $_characters = '0123456789';

    /**
     * Checksum function
     * @var string
     */
    protected $_checksum = '_identcode';
}
