<?php

	class qa_mobbr_cronjob {

        function match_request($request)
        {
            return ($request==qa_opt('mobbr_support_cronjob_url'));
        }

        function process_request($request)
        {
            header('Content-type: text/plain; charset=utf-8');
            error_log( 'starting cronjob' );

            require_once 'qa-mobbr-queries.php';

            // ------------------------------------------------------------------------
            // every cronjob we reduce the point with a precentage, so people who stop
            // collaborating won't be sharing till the end of times
            // ------------------------------------------------------------------------

            $percentage = qa_opt('mobbr_support_cronjob_decay_percentage');
            qa_db_query_sub(qa_db_mobbr_question_type_query(intval($percentage)));

            error_log( 'finished cronjob' );
            return null;
        }

	}


/*
	Omit PHP closing tag to help avoid accidental output
*/

