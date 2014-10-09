<?php

	class qa_mobbr_resolver {

        function match_request($request)
        {
            return (strpos($request, 'user_') === 0);
        }

        function process_request($request)
        {
            $parts = explode('_', $request);
            $id = $parts[1];
            if (defined('QA_WORDPRESS_INTEGRATE_PATH'))
            {
                $user = get_user_by('slug', $id);
                echo $user->user_email;
            }
            else
            {
                @ini_set('display_errors', 0); // we don't want to show PHP errors inside XML
                header('Content-type: text/plain; charset=utf-8');
                $user = qa_db_single_select(qa_db_user_account_selectspec($id, FALSE));
                echo $user['email'];
            }
            return null;
        }

	}


/*
	Omit PHP closing tag to help avoid accidental output
*/

