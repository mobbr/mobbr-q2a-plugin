<?php

    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    function qa_mobbr_www_url()
    {
        $environment = qa_opt('mobbr_support_environment');
        return $environment === 'test' ? 'https://test-www.mobbr.com' : 'https://mobbr.com';
    }

    function qa_mobbr_api_url()
    {
        $environment = qa_opt('mobbr_support_environment');
        return $environment === 'test' ? 'https://test-api.mobbr.com' : 'https://api.mobbr.com';
    }

/*
    Omit PHP closing tag to help avoid accidental output
*/