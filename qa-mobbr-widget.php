<?php

    class qa_mobbr_widget {

        var $buttontypes = array(
            'Default', 'Large', 'Flat', 'Small', 'Slim'
        );

        function option_default($option)
        {
            if ($option=='mobbr_support_buttontype')
                return 2;
            /*elseif ($option=='tag_cloud_font_size')
                return 24;
            elseif ($option=='tag_cloud_size_popular')
                return true;*/
        }

        function admin_form()
        {
            $saved=false;

            if (qa_clicked('mobbr-support-save')) {
                qa_opt('mobbr_support_buttontype', qa_post_text('mobbr_support_buttontype_field'));
                $saved=true;
            }

            return array(
                'ok' => $saved ? 'Mobbr settings saved' : null,

                'fields' => array(
                    array(
                        'label' => 'Button type:',
                        'type' => 'select',
                        'value' => $this->buttontypes[qa_opt('mobbr_support_buttontype')],
                        'options' => array('Default', 'Large', 'Flat', 'Small', 'Slim'),
                        'tags' => 'name="mobbr_support_buttontype_field"',
                    ),
                ),

                'buttons' => array(
                    array(
                        'label' => 'Save Changes',
                        'tags' => 'name="mobbr-support-save"',
                    ),
                ),
            );
        }

        function allow_template($template)
        {
            switch ($template)
            {
                //case 'activity':
                //case 'qa':
                //case 'questions':
                //case 'hot':
                //case 'ask':
                //case 'categories':
                //case 'tags':
                //case 'unanswered':
                //case 'users':
                //case 'search':
                //case 'admin':
                //case 'custom':
                case 'question':
                case 'tag':
                case 'categories':
                case 'user':
                case 'qa':
                    return true;
                default:
                    return false;
            }
        }

        function allow_region($region)
        {
            //return ($region=='side');
            return true;
        }

        function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
        {
            require_once 'qa-mobbr-url.php';
            $buttontype = qa_opt('mobbr_support_buttontype');
            $themeobject->output(
                '<script src="' . qa_mobbr_www_url() . '/mobbr-button.js" type="text/javascript"></script>',
                '<script type="text/javascript">mobbr.button' . (($buttontype !== 0) ? $this->buttontypes[$buttontype] : '') . '("' . qa_mobbr_www_url() . '/#/buttons");</script>'
            );
            /*require_once QA_INCLUDE_DIR.'qa-db-selects.php';

            $populartags=qa_db_single_select(qa_db_popular_tags_selectspec(0, (int)qa_opt('tag_cloud_count_tags')));

            reset($populartags);
            $maxcount=current($populartags);

            $themeobject->output(
                '<h2 style="margin-top:0; padding-top:0;">',
                qa_lang_html('main/popular_tags'),
                '</h2>'
            );

            $themeobject->output('<div style="font-size:10px;">');

            $maxsize=qa_opt('tag_cloud_font_size');
            $scale=qa_opt('tag_cloud_size_popular');

            foreach ($populartags as $tag => $count) {
                $size=number_format(($scale ? ($maxsize*$count/$maxcount) : $maxsize), 1);

                if (($size>=5) || !$scale)
                    $themeobject->output('<a href="'.qa_path_html('tag/'.$tag).'" style="font-size:'.$size.'px; vertical-align:baseline;">'.qa_html($tag).'</a>');
            }

            $themeobject->output('</div>');*/
        }

    }


/*
	Omit PHP closing tag to help avoid accidental output
*/