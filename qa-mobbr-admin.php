<?php

    class qa_mobbr_admin {

        function init_queries($tableslc)
        {
            // initialization neccessairy
        }

        function option_default($option)
        {
            if ($option=='mobbr_support_badgetype')
                return 1;
            elseif ($option=='mobbr_support_currency')
                return 'EUR';
            elseif ($option=='mobbr_support_show_button_in_lists')
                return false;
            elseif ($option=='mobbr_support_language')
                return 'EN';
            elseif ($option=='mobbr_support_buttontype')
                return 2;
            elseif ($option=='mobbr_support_title_link')
                return '';
            elseif ($option=='mobbr_support_title')
                return '';
            elseif ($option=='mobbr_support_button_html')
                return '{{button}}';
            elseif ($option=='mobbr_support_badge_html')
                return '{{badge}}';
            elseif ($option=='mobbr_support_platform_percentage')
                return 30;
            elseif ($option=='mobbr_support_selected_answer_bonus')
                return 10;
            /*elseif ($option=='mobbr_support_community_percentage')
                return 30;
            elseif ($option=='mobbr_support_cronjob_url')
                return 'cronjob_86534402';
            elseif ($option=='mobbr_support_cronjob_decay_percentage')
                return 5;
            elseif ($option=='mobbr_support_scripttype')
                return 'meta';*/
            else
                return '';
        }

        function admin_form()
        {
            // include
            require_once 'qa-mobbr.php';
            $saved=false;

            if (qa_clicked('mobbr-support-save-changes')) {
                qa_opt('mobbr_support_environment', qa_post_text('mobbr_support_environment_field'));
                qa_opt('mobbr_support_language', qa_post_text('mobbr_support_language_field'));
                qa_opt('mobbr_support_badgetype', qa_post_text('mobbr_support_badgetype_field'));
                qa_opt('mobbr_support_currency', qa_post_text('mobbr_support_currency_field'));
                qa_opt('mobbr_support_show_button_in_lists', (bool) qa_post_text('mobbr_support_show_button_in_lists_field'));
                qa_opt('mobbr_support_buttontype', qa_post_text('mobbr_support_buttontype_field'));
                qa_opt('mobbr_support_title_link', qa_post_text('mobbr_support_title_link_field'));
                qa_opt('mobbr_support_title', qa_post_text('mobbr_support_title_field'));
                qa_opt('mobbr_support_button_html', qa_post_text('mobbr_support_button_html_field'));
                qa_opt('mobbr_support_badge_html', qa_post_text('mobbr_support_badge_html_field'));
                qa_opt('mobbr_support_platform_account', qa_post_text('mobbr_support_platform_account_field'));
                qa_opt('mobbr_support_platform_percentage', qa_post_text('mobbr_support_platform_percentage_field'));
                qa_opt('mobbr_support_selected_answer_bonus', qa_post_text('mobbr_support_selected_answer_bonus_field'));
                /*qa_opt('mobbr_support_cronjob_decay_percentage', qa_post_text('mobbr_support_cronjob_decay_percentage_field'));
                qa_opt('mobbr_support_cronjob_url', qa_post_text('mobbr_support_cronjob_url_field'));
                qa_opt('mobbr_support_scripttype', qa_post_text('mobbr_support_scripttype_field'));
                qa_opt('mobbr_support_community_percentage', qa_post_text('mobbr_support_community_percentage_field'));*/
                $saved=true;
            }

            return array(
                'ok' => $saved ? 'Mobbr settings saved' : null,

                'fields' => array(
                    array(
                        'label' => 'Language:',
                        'type' => 'select',
                        'value' => qa_mobbr::$languages[qa_opt('mobbr_support_language')],
                        'options' => qa_mobbr::$languages,
                        'tags' => 'name="mobbr_support_language_field"',
                        'note' => '<small>Set the language of the questions</small>',
                    ),
                    array(
                        'label' => 'Mobbr API:',
                        'type' => 'select',
                        'value' => qa_mobbr::$environments[qa_opt('mobbr_support_environment')],
                        'options' => qa_mobbr::$environments,
                        'tags' => 'name="mobbr_support_environment_field"',
                        'note' => '<small>Set to Test during development. No real payment are made in Test. Our test site is at <a target="_blank" href="https://test-www.mobbr.com">https://test-www.mobbr.com</a>.</small>',
                    ),
                    array(
                        'label' => 'Button type:',
                        'type' => 'select',
                        'value' => qa_mobbr::$buttontypes[qa_opt('mobbr_support_buttontype')],
                        'options' => qa_mobbr::$buttontypes,
                        'tags' => 'name="mobbr_support_buttontype_field"',
                        'note' => '<small>Choose which button you would like to see. To place buttons go to the <a href="http://ask.mobbr.com/admin/layout">layout menu</a></small>',
                    ),
                    array(
                        'label' => 'Button currency:',
                        'type' => 'select',
                        'value' => qa_mobbr::$currencies[qa_opt('mobbr_support_currency')],
                        'options' => qa_mobbr::$currencies,
                        'tags' => 'name="mobbr_support_currency_field"',
                        'note' => '<small>Choose which currency to show in the button. All payments are shown in this currency based on actual exchange rates.</small>',
                    ),
                    array(
                        'label' => 'Show button in question lists',
                        'type' => 'checkbox',
                        'value' => (bool) qa_opt('mobbr_support_show_button_in_lists'),
                        'tags' => 'name="mobbr_support_show_button_in_lists_field"',
                        'note' => '<small>Check if you want to see buttons in the questions lists. This can <strong>NOT</strong> be controlled through the <a href="http://ask.mobbr.com/admin/layout">layout menu</a>. Using this option might require a change to your theme.</small>',
                    ),
                    array(
                        'label' => 'Button widget:',
                        'type' => 'textarea',
                        'rows' => 3,
                        'value' => qa_opt('mobbr_support_button_html') ? qa_opt('mobbr_support_button_html') : '{{button}}',
                        'tags' => 'name="mobbr_support_button_html_field"',
                        'note' => '<small>HTML that will be added inline to the page, {{button}} is the placeholder for the button and should always be here</small>',
                    ),
                    array(
                        'label' => 'Platform account:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_platform_account'),
                        'tags' => 'name="mobbr_support_platform_account_field"',
                        'note' => '<small>Mobbr username that is always included in the recipient-list, usually the platform owner. May be empty. Example: Patrick</small>'
                    ),
                    array(
                        'label' => 'Platform percentage:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_platform_percentage'),
                        'tags' => 'name="mobbr_support_platform_percentage_field"',
                        'note' => '<small>Percentage of revenue that is paid to platform account above. May be empty. Example: 10</small>'
                    ),
                    array(
                        'label' => 'Selected answer bonus:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_selected_answer_bonus'),
                        'tags' => 'name="mobbr_support_selected_answer_bonus_field"',
                        'note' => '<small>Percentage of revenue for the selected answer, may be empty. Example: 10</small>'
                    ),
                ),

                'buttons' => array(
                    array(
                        'label' => 'Save Changes',
                        'tags' => 'name="mobbr-support-save-changes"',
                    ),
                ),
            );
        }
    }