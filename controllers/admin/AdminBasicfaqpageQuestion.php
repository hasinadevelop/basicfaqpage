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
require_once _PS_MODULE_DIR_ . '/basicfaqpage/classes/BasicfaqpageQuestion.php';

class AdminBasicfaqpageQuestionController extends \ModuleAdminController
{
    protected $_defaultOrderBy = 'id';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = BasicfaqpageQuestion::$definition['table'];
        $this->identifier = BasicfaqpageQuestion::$definition['primary'];
        $this->className = BasicfaqpageQuestion::class;

        parent::__construct();

        $this->fields_list = [
            'id' => [
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'question' => [
                'title' => $this->module->l('Question'),
                'align' => 'left',
            ],
            'status' => [
                'title' => $this->module->l('Status'),
                'align' => 'center',
                'callback' => 'renderStatusIcon',
            ],
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['new'] = [
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->module->l('Add new question'),
            'icon' => 'process-icon-new',
        ];

        parent::initPageHeaderToolbar();
    }


    public function renderStatusIcon($status)
    {
        return $status ? '<b><i class="material-icons text-success">check</i></b>' : '<b><i class="material-icons text-danger">close</i></b>';
    }

    public function renderForm()
    {

        $this->fields_form = [
            'legend' => [
                'title' => $this->module->l('Edit question'),
                'icon' => 'icon-cog',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->module->l('Question'),
                    'name' => 'question',
                    'class' => 'input',
                    'size' => 50,
                    'required' => true,
                    'empty_message' => $this->l('Please fill the question'),
                    'desc' => $this->module->l('Enter the question.'),
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->module->l('Answer'),
                    'name' => 'answer',
                    'class' => 'rte',
                    'required' => true,
                    'empty_message' => $this->l('Please fill answer'),
                    'desc' => $this->module->l('Enter the answer.'),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->module->l('Status'),
                    'name' => 'status',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'status_on',
                            'value' => 1,
                            'label' => $this->module->l('Enabled'),
                        ],
                        [
                            'id' => 'status_off',
                            'value' => 0,
                            'label' => $this->module->l('Disabled'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        return parent::renderForm();
    }

    public function getFieldsValue($obj)
    {
        foreach ($this->fields_form as $fieldset) {
            if (isset($fieldset['form']['input'])) {
                foreach ($fieldset['form']['input'] as $input) {
                    if (!isset($this->fields_value[$input['name']])) {
                        if (isset($input['type']) && $input['type'] == 'shop') {
                            if ($obj->id) {
                                $result = \Shop::getShopById((int) $obj->id, $this->identifier, $this->table);
                                foreach ($result as $row) {
                                    $this->fields_value['shop'][$row['id_' . $input['type']]][] = $row['id_shop'];
                                }
                            }
                        } elseif (isset($input['lang']) && $input['lang']) {
                            foreach ($this->_languages as $language) {
                                $field_value = $this->getFieldValue($obj, $input['name'], $language['id_lang']);
                                if (empty($field_value)) {
                                    if (isset($input['default_value']) && is_array($input['default_value']) && isset($input['default_value'][$language['id_lang']])) {
                                        $field_value = $input['default_value'][$language['id_lang']];
                                    } elseif (isset($input['default_value'])) {
                                        $field_value = $input['default_value'];
                                    }
                                }
                                $this->fields_value[$input['name']][$language['id_lang']] = $field_value;
                            }
                        } else {
                            $field_value = $this->getFieldValue($obj, $input['name']);
                            if ($field_value === false && isset($input['default_value'])) {
                                $field_value = $input['default_value'];
                            }
                            $this->fields_value[$input['name']] = $field_value;
                        }
                    }
                }
            }
        }

        return $this->fields_value;
    }

    public function processSave()
    {
        if ($this->id_object) {
            $this->object = $this->loadObject();

            return $this->processUpdate();
        } else {
            return $this->processAdd();
        }
    }
}
