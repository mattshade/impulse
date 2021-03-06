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

/** IfwPsn_Vendor_Zend_Form_Element_Multi */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Form/Element/Multi.php';

/**
 * Radio form element
 *
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Radio.php 1411129 2016-05-05 16:15:58Z worschtebrot $
 */
class IfwPsn_Vendor_Zend_Form_Element_Radio extends IfwPsn_Vendor_Zend_Form_Element_Multi
{
    /**
     * Use formRadio view helper by default
     * @var string
     */
    public $helper = 'formRadio';

    /**
     * Load default decorators
     *
     * Disables "for" attribute of label if label decorator enabled.
     *
     * @return IfwPsn_Vendor_Zend_Form_Element_Radio
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        parent::loadDefaultDecorators();

        // Disable 'for' attribute
        if (isset($this->_decorators['Label'])
            && !isset($this->_decorators['Label']['options']['disableFor']))
        {
             $this->_decorators['Label']['options']['disableFor'] = true;
        }

        return $this;
    }
}
