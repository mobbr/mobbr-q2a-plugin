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
            elseif ($option=='mobbr_support_cronjob_url')
                return 'cronjob_86534402';
            elseif ($option=='mobbr_support_community_percentage')
                return 30;
            elseif ($option=='mobbr_support_selected_answer_bonus')
                return 10;
            elseif ($option=='mobbr_support_platform_percentage')
                return 30;
            elseif ($option=='mobbr_support_cronjob_decay_percentage')
                return 5;
            elseif ($option=='mobbr_support_scripttype')
                return 'meta';
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
                qa_opt('mobbr_support_buttontype', qa_post_text('mobbr_support_buttontype_field'));
                qa_opt('mobbr_support_title_link', qa_post_text('mobbr_support_title_link_field'));
                qa_opt('mobbr_support_title', qa_post_text('mobbr_support_title_field'));
                qa_opt('mobbr_support_cronjob_decay_percentage', qa_post_text('mobbr_support_cronjob_decay_percentage_field'));
                qa_opt('mobbr_support_cronjob_url', qa_post_text('mobbr_support_cronjob_url_field'));
                qa_opt('mobbr_support_button_html', qa_post_text('mobbr_support_button_html_field'));
                qa_opt('mobbr_support_badge_html', qa_post_text('mobbr_support_badge_html_field'));
                qa_opt('mobbr_support_platform_account', qa_post_text('mobbr_support_platform_account_field'));
                qa_opt('mobbr_support_platform_percentage', qa_post_text('mobbr_support_platform_percentage_field'));
                qa_opt('mobbr_support_community_percentage', qa_post_text('mobbr_support_community_percentage_field'));
                qa_opt('mobbr_support_selected_answer_bonus', qa_post_text('mobbr_support_selected_answer_bonus_field'));
                qa_opt('mobbr_support_scripttype', qa_post_text('mobbr_support_scripttype_field'));
                $saved=true;
            }

            return array(
                'ok' => $saved ? 'Mobbr settings saved' : null,

                'fields' => array(
                    array(
                        'label' => 'Mobbr API:',
                        'type' => 'select',
                        'value' => qa_mobbr::$environments[qa_opt('mobbr_support_environment')],
                        'options' => qa_mobbr::$environments,
                        'tags' => 'name="mobbr_support_environment_field"',
                    ),
                    array(
                        'label' => 'Script type:',
                        'type' => 'select',
                        'value' => qa_mobbr::$scripttypes[qa_opt('mobbr_support_scripttype')],
                        'options' => qa_mobbr::$scripttypes,
                        'tags' => 'name="mobbr_support_scripttype_field"',
                    ),
                    array(
                        'label' => 'Button type:',
                        'type' => 'select',
                        'value' => qa_mobbr::$buttontypes[qa_opt('mobbr_support_buttontype')],
                        'options' => qa_mobbr::$buttontypes,
                        'tags' => 'name="mobbr_support_buttontype_field"',
                    ),
                    array(
                        'label' => 'Button HTML:',
                        'type' => 'textarea',
                        'value' => qa_opt('mobbr_support_button_html') ? qa_opt('mobbr_support_button_html') : '{{button}}',
                        'tags' => 'name="mobbr_support_button_html_field"',
                        'note' => 'Will be added inline to the page, {{button}} is the placeholder for Mobbr button-code',
                    ),
                    array(
                        'label' => 'Badge HTML:',
                        'type' => 'textarea',
                        'value' => qa_opt('mobbr_support_badge_html') ? qa_opt('mobbr_support_badge_html') : '{{badge}}',
                        'tags' => 'name="mobbr_support_badge_html_field"',
                        'note' => 'Will be added inline to the page, {{badge}} is the placeholder for Mobbr badge-code',
                    ),
                    array(
                        'label' => 'Badge type:',
                        'type' => 'select',
                        'value' => qa_mobbr::$badgetypes[qa_opt('mobbr_support_badgetype')],
                        'options' => qa_mobbr::$badgetypes,
                        'tags' => 'name="mobbr_support_badgetype_field"',
                    ),
                    array(
                        'label' => 'Displayed currency:',
                        'type' => 'select',
                        'value' => qa_mobbr::$currencies[qa_opt('mobbr_support_currency')],
                        'options' => qa_mobbr::$currencies,
                        'tags' => 'name="mobbr_support_currency_field"',
                    ),
                    array(
                        'label' => 'Platform language:',
                        'type' => 'select',
                        'value' => qa_mobbr::$languages[qa_opt('mobbr_support_language')],
                        'options' => qa_mobbr::$languages,
                        'tags' => 'name="mobbr_support_language_field"',
                    ),
                    array(
                        'label' => 'Title for badge widget:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_title'),
                        'tags' => 'name="mobbr_support_title_field"',
                    ),
                    array(
                        'label' => 'URL for link in badge widget:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_title_link'),
                        'tags' => 'name="mobbr_support_title_link_field"',
                    ),
                    array(
                        'label' => 'Cronjob decay percentage:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_cronjob_decay_percentage'),
                        'tags' => 'name="mobbr_support_cronjob_decay_percentage_field"',
                        'note' => 'The percentage with which all user points are reduced on every cronjob call (see below).'
                    ),
                    array(
                        'label' => 'Cronjob callback page:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_cronjob_url'),
                        'tags' => 'name="mobbr_support_cronjob_url_field"',
                        'note' => 'Make sure this URL (/'.qa_opt('mobbr_support_cronjob_url').') is called every week, other week or month, it handles point decay. If possible use .htaccess to protect from misuse. Must be a unique page and unguessable.'
                    ),
                    array(
                        'label' => 'Platform account:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_platform_account'),
                        'tags' => 'name="mobbr_support_platform_account_field"',
                        'note' => 'Mobbr name or email of account that is always included in the recipient-list'
                    ),
                    array(
                        'label' => 'Platform percentage:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_platform_percentage'),
                        'tags' => 'name="mobbr_support_platform_percentage_field"',
                        'note' => 'Percentage of button revenue that is paid to platform account'
                    ),
                    array(
                        'label' => 'Community percentage:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_community_percentage'),
                        'tags' => 'name="mobbr_support_community_percentage_field"',
                        'note' => 'Percentage of button revenue that is divided among community members based on their points'
                    ),
                    array(
                        'label' => 'Selected answer bonus:',
                        'type' => 'text',
                        'value' => qa_opt('mobbr_support_selected_answer_bonus'),
                        'tags' => 'name="mobbr_support_selected_answer_bonus_field"',
                        'note' => 'Percentage of button revenue for the selected answer'
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