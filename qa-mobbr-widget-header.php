<?php

    class qa_mobbr_widget_header {

        function allow_template($template)
        {
            return true;
            /*$allow=false;

            switch ($template)
            {
                case 'activity':
                case 'hot':
                case 'ask':
                case 'categories':
                case 'tags':
                case 'unanswered':
                case 'users':
                case 'search':
                case 'admin':
                case 'custom':
                case 'question':
                case 'tag':
                case 'questions':
                case 'user':
                case 'qa':
                    $allow=true;
                    break;
            }

            return $allow; */
        }


        function allow_region($region)
        {
            //return ($region=='side');
            return true;
        }


        function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
        {
            require_once 'qa-mobbr.php';
            require_once 'qa-mobbr-url.php';
            $buttontype = qa_opt('mobbr_support_badgetype');
            $currency = qa_opt('mobbr_support_currency');
            $themeobject->output(
                '<div style="position: relative;" class="qa-widget-side">',
                '  <div style="width:200px;float:left;" class="headercontainer">',
                '  <script src="' . qa_mobbr_www_url() . '/mobbr-button.js" type="text/javascript"></script>',
                '  <script type="text/javascript">mobbr.badge' . qa_mobbr::$badgetypes[$buttontype] . '("' . $_SERVER['HTTP_HOST'] . '", "' . ($currency == '' ? '' : qa_opt('mobbr_support_currency')) . '");</script>',
                '  </div>',
                '  <div style="float:right">',
                '    <div style="margin-top: 0.2em;">',
                '      <div class="qa-activity-count-data">',
                '        <a href="'.qa_opt('mobbr_support_title_link').'">'.qa_opt('mobbr_support_title').'</a>',
                '      </div>',
                '    </div>',
                '  </div>',
                '  <span style="display: block; clear: both;">',
                '  </span>',
                '</div>'
            );
        }

    }


/*
	Omit PHP closing tag to help avoid accidental output
*/