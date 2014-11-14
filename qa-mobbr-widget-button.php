<?php

    class qa_mobbr_widget_button {

        function allow_template($template)
        {
            return true;
            /*switch ($template)
            {
                case 'questions':
                case 'question':
                case 'tag':
                case 'user':
                    return true;
                default:
                    return false;
            }*/
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
            require_once 'qa-mobbr-layer.php';

            $buttontype = qa_mobbr::$buttontypes[qa_opt('mobbr_support_buttontype')];
            $currency = qa_opt('mobbr_support_currency');
            $buttonhtml = '<script type="text/javascript">mobbr.button' . $buttontype. '(' . '"", "' . $currency . '");</script>';
            $html = qa_opt('mobbr_support_button_html');
            if (strpos($html, '{{button}}')) {
                $html = str_replace('{{button}}', $buttonhtml, $html);
            } else {
                $html = $buttonhtml;
            }
            $themeobject->output($html );
        }
    }


/*
	Omit PHP closing tag to help avoid accidental output
*/