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
 * @package    IfwPsn_Vendor_Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** IfwPsn_Vendor_Zend_Form_Element_Submit */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Form/Element/Submit.php';

/**
 * Button form element
 *
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Button.php 1411129 2016-05-05 16:15:58Z worschtebrot $
 */
class IfwPsn_Vendor_Zend_Form_Element_Button extends IfwPsn_Vendor_Zend_Form_Element_Submit
{
    /**
     * Use formButton view helper by default
     * @var string
     */
    public $helper = 'formButton';

    /**
     * Validate element value (pseudo)
     *
     * There is no need to reset the value
     *
     * @param  mixed $value Is always ignored
     * @param  mixed $context Is always ignored
     * @return boolean Returns always TRUE
     */
    public function isValid($value, $context = null)
    {
        return true;
    }
}