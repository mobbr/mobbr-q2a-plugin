<?php

    class qa_mobbr_frontend {

        static function get_html_button()
        {
            require_once 'qa-mobbr.php';

            $buttontype = qa_mobbr::$buttontypes[qa_opt('mobbr_support_buttontype')];
            $currency = qa_opt('mobbr_support_currency');
            $buttonhtml = '<script type="text/javascript">mobbr.button' . $buttontype. '(' . '"", "' . $currency . '");</script>';
            $html = qa_opt('mobbr_support_button_html');
            if (strpos($html, '{{button}}')) {
                $html = str_replace('{{button}}', $buttonhtml, $html);
            } else {
                $html = $buttonhtml;
            }
            return $html;
        }

    }


/*
	Omit PHP closing tag to help avoid accidental output
*/
