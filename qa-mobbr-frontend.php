<?php

    class qa_mobbr_frontend {

        static function get_html_button($questionid = null)
        {
            require_once 'qa-mobbr.php';

            $buttontype = qa_mobbr::$buttontypes[qa_opt('mobbr_support_buttontype')];
            $currency = qa_opt('mobbr_support_currency');
            $url = isset($questionid) ? qa_q_path($questionid, '', true) : '';
            $buttonhtml = sprintf('<script type="text/javascript">mobbr.button%s("%s", "%s");</script>', $buttontype, $url, $currency);
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
