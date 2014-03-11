<?php

	class qa_html_theme_layer extends qa_html_theme_base {


        public function id_base()
        {
            if (QA_EXTERNAL_USERS)
            {
                $environment = qa_opt('mobbr_support_environment');
                return $environment === 'test' ? 'https://test-api.mobbr.com/id/' : 'https://api.mobbr.com/id/';
            }
            else
            {
                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                return  $protocol . $_SERVER['HTTP_HOST'] .  $_SERVER['PHP_SELF'] . '?qa=mobbr_';
            }
        }

        // --------------------------------------------------------------------

        function get_user_id($id)
        {
            if (defined('QA_WORDPRESS_INTEGRATE_PATH'))
            {
                $user = get_user_by('id', $id);
                return $user->user_nicename;
            }
            else
            {
                return $id;
            }
        }

        // --------------------------------------------------------------------

        function question_meta($postid)
        {
            // ----------------------------------------------------------------
            // Return the payment script for a question
            // ----------------------------------------------------------------

            // script basics
            $type = qa_db_single_select(qa_db_mobbr_question_type_query(intval($postid)));
            $type= $type[0];
            $meta = array(
                "id-base" => $this->id_base(),
                "type" => $type['type'],
                "language" => qa_opt('mobbr_support_language') );

            // add question repliers, no percentages but ratios
            $answer_ratios = qa_db_single_select(qa_db_mobbr_question_ratio_query(intval($postid)));

            if (!isset($meta['participants']))
            {
                $meta['participants'] = array();
            }

            // add platform owner
            $platform_percentage = qa_opt('mobbr_support_platform_percentage');
            $platform_account = qa_opt('mobbr_support_platform_account');
            if ( !empty( $platform_account ) )
            {
                if ( empty( $platform_percentage ) ) $platform_percentage = 10;
                $meta['participants'][] = array(
                    "id" => $platform_account,
                    "role" => "QA platform owner",
                    "share" =>  $platform_percentage . "%" );
            }

            if (!empty( $answer_ratios ))
            {
                foreach( $answer_ratios as $ratio )
                {
                    $id = $this->get_user_id($ratio['userid']);
                    $meta['participants'][] = array( "id" => $id, "share" => $ratio['count'], "role" => "QA thread answerer" );
                }

                // also add the community members
                $community_percentage = qa_opt('mobbr_support_community_percentage');
                $community_ratios = qa_db_single_select(qa_db_mobbr_reputation_query());
                if ( empty( $community_percentage ) ) $community_percentage = 30;
                // convert community members points to percentages, first sum all points
                $total = 0;
                // first get total count
                foreach( $community_ratios as $ratio )
                {
                    $total +=  $ratio['points'];
                }
                // add participants and convert points to percentages
                foreach( $community_ratios as $ratio )
                {
                    $id = $this->get_user_id($ratio['userid']);
                    $share = floor( $ratio['points'] / $total * $community_percentage );
                    if ($share > 0)
                    {
                        $meta['participants'][] = array( "id" => $id, "share" => $share . "%", "role" => "QA community member" );
                    }
                }
            }
            else
            {
                $ratios = qa_db_single_select(qa_db_mobbr_reputation_query());
                foreach( $ratios as $ratio )
                {
                    $id = $this->get_user_id($ratio['userid']);
                    $meta['participants'][] = array( "id" => $id, "share" => $ratio['points'], "role" => "QA community member" );
                }
            }
            return $meta;
        }

        // --------------------------------------------------------------------

        function person_meta($username)
        {
            return array(
                "id-base" => $this->id_base(),
                "participants" => array(
                    "id" => $username,
                    "role" => "Member",
                    "share" => "1" ) );
            return $meta;
        }

        // --------------------------------------------------------------------

        function tag_meta($tag)
        {
            return NULL;
        }

        // --------------------------------------------------------------------

        function category_meta($category)
        {
            return $this->questions_meta();
        }

        // --------------------------------------------------------------------

        function questions_meta()
        {
            $ratios = qa_db_single_select(qa_db_mobbr_reputation_query());
            $meta = array(
                "id-base" => $this->id_base(),
                "description" => "Donation to all community members, based on the points the earned.", );
            $meta['participants'] = array();
            $platform_percentage = qa_opt('mobbr_support_platform_percentage');
            if ( empty( $platform_percentage ) ) $platform_percentage = 25;
            if ( !empty( $platform_account ) )
            {
                $meta['participants'][] = array(
                    "id" => qa_opt('mobbr_support_platform_account'),
                    "role" => "QA platform owner",
                    "share" =>  $platform_percentage . "%" );
            }
            foreach( $ratios as $ratio )
            {
                $id = $this->get_user_id($ratio['userid']);
                $meta['participants'][] = array( "id" => $id, "share" => $ratio['points'], "role" => "QA community member" );
            }
            return $meta;
        }

        // --------------------------------------------------------------------

		function head_links()
		{
		    // -----------------------------------------------------------
		    // We detect on which page type we are and generate the script
		    // that is needed for that particular page type.
		    // -----------------------------------------------------------

            require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-widget-button.php';
            require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-queries.php';

            $environment = qa_opt('mobbr_support_environment');
            $this->output('<script type="text/javascript" src="' . ($environment === 'test' ? 'https://test-www.mobbr.com' : 'https://mobbr.com') . '/mobbr-button.js"></script>');
            if (defined('QA_MOBBR_SSO') && QA_MOBBR_SSO && defined('QA_EXTERNAL_USERS') && QA_EXTERNAL_USERS) {
                // only for Mobbr SSO
                $this->output('<script>mobbrSSO.enable();</script>');
            }
            $page = qa_request_part(0);
            $sub = qa_request_part(1);
            qa_html_theme_base::head_links();
            $meta = array();
            if (is_numeric($page))
            {
                $meta = $this->question_meta($page);
            }
            elseif ($page === 'user')
            {
                $meta = $this->person_meta($sub);
            }
            elseif ($page === 'questions')
            {
                if (empty($sub))
                {
                    // nothing yet
                }
                else
                {
                    // nothing yet
                }
            }
            else if ($page === 'tag')
            {
                    // nothing yet
            }
            else if ($page === 'users')
            {
                // nothing yet
            }
            // all our pages have metadata, default is 'reward all members based on recently earned points'
            if (empty($meta))
            {
                $meta = $this->questions_meta();
            }
            if (!empty( $meta ) )
            {
                $this->output('<meta name="participation" content=\''.json_encode($meta).'\'/>');
            }
        }
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/