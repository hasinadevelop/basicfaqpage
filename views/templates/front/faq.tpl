{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
 {extends file='page.tpl'}

{block name='page_header_container'}{/block}

{block name='page_content'}
    <h2 class="basicfaqpage-title">{l s='Frequently asked questions' d='Modules.Basicfaqpage.Shop'}</h2>
    <p>{l s="Here you will find the answers to our users' questions" d='Modules.Basicfaqpage.Shop'}</p>

    <hr>

    <div class="basicfaqpage-questions">
        {foreach $questions as $question}
            <div class="basicfaqpage-item">
                <h3>{$question->question}</h3>
                <p>{$question->answer nofilter}</p>
            </div>
            <div class="basicfaqpage-item">
                <h3>{$question->question}</h3>
                <p>{$question->answer nofilter}</p>
            </div>
            <div class="basicfaqpage-item">
                <h3>{$question->question}</h3>
                <p>{$question->answer nofilter}</p>
            </div>
        {/foreach}
    </div>
{/block}
