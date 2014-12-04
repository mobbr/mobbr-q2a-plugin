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
            require_once 'qa-mobbr-frontend.php';

            $themeobject->output(qa_mobbr_frontend::get_html_button());
        }
    }


/*
	Omit PHP closing tag to help avoid accidental output
*/