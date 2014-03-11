<?php

    class qa_mobbr_widget_badge {

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

            return $allow;*/
        }


        function allow_region($region)
        {
            //return ($region=='side');
            return true;
        }

        function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
        {
            require_once 'qa-mobbr-url.php';
            $badgetype = qa_mobbr::$badgetypes[qa_opt('mobbr_support_badgetype')];
            $currency = qa_opt('mobbr_support_currency');
            $buttonhtml = '<script src="' . qa_mobbr_www_url() . '/mobbr-button.js" type="text/javascript"></script>' .
                '<script type="text/javascript">mobbr.badge' . $badgetype . '("' . $_SERVER['HTTP_HOST'] . '", "' . $currency . '");</script>';
            $html = qa_opt('mobbr_support_badge_html');
            if (strpos($html, '{{badge}}')) {
                $html = str_replace('{{badge}}', $buttonhtml, $html);
            } else {
                $html = $buttonhtml;
            }
            $themeobject->output($html);
        }
    }


/*
	Omit PHP closing tag to help avoid accidental output
*/