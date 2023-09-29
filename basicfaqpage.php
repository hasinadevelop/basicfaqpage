<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

class Basicfaqpage extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'basicfaqpage';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Hasina Adnriantseheno';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Frequently Asked Questions Basic');
        $this->description = $this->l('Add FAQ page to your shop');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        require_once _PS_MODULE_DIR_ . '/basicfaqpage/sql/install.php';

        return parent::install() &&
            $this->initTab() && 
            $this->registerHook('header');
    }

    public function uninstall()
    {
        require_once _PS_MODULE_DIR_ . '/basicfaqpage/sql/uninstall.php';

        return $this->deinitTab() && parent::uninstall();
    }

    private function initTab()
    {
        $tabQuestion = new Tab();
        $tabQuestion->class_name = 'AdminBasicfaqpageQuestion';
        $tabQuestion->module = $this->name;
        $tabQuestion->id_parent = (int) Tab::getIdFromClassName('DEFAULT');
        $tabQuestion->icon = 'settings_applications';
        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            $tabQuestion->name[$lang['id_lang']] = $this->l('FAQ');
        }
        $tabQuestion->save();

        try {
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return true;
    }

    private function deinitTab()
    {
        $idTabQuestion = (int) Tab::getIdFromClassName('AdminBasicfaqpageQuestion');
        
        if ($idTabQuestion) {
            $tab = new Tab($idTabQuestion);
            try {
                $tab->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
