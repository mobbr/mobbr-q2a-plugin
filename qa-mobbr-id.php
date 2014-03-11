<?php

class qa_mobbr_id {

    function match_request($request)
    {
        return (strpos($request, 'mobbr_id_') === 0);
    }

    function process_request($request)
    {
        $parts = explode('_', $request);
        $id = $parts[2];
        if (defined('QA_WORDPRESS_INTEGRATE_PATH'))
        {
            $user = get_user_by('slug', $id);
            echo $user->user_email;
        }
        return null;
    }

}