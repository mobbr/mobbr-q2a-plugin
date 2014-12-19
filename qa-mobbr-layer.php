<?php

	class qa_html_theme_layer extends qa_html_theme_base
    {


        public function id_base()
        {
            if (QA_EXTERNAL_USERS)
            {
                //$environment = qa_opt('mobbr_support_environment');
                return 'https://mobbr.com/#/person/';
            }
            else
            {
                return  qa_opt('site_url') . '?qa=user_';
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

            $type = qa_db_single_select(qa_db_mobbr_question_type_query(intval($postid)));
            $answerer = $type[0]['userid'];
            $type = $type[0]['type'];

            $meta = array(
                "id-base" => $this->id_base(),
                "type" => $type,
                "language" => qa_opt('mobbr_support_language'),
                "keywords" => array("qa", "question2answer"),
                "message"  => ($type=='pledge'
                        ? "Pledge money for solving this question. The money will be divided among contributors by ratio of votes, once a best answer is accepted"
                        : "Pay to all contributors in this thread. The money will be instantly divided among contributors by ratio of votes" ),
                "participants" => array() );

            // add question repliers, no percentages but ratios
            $answer_ratios = qa_db_single_select(qa_db_mobbr_question_ratio_query(intval($postid)));
            if (!empty( $answer_ratios ))
            {
                foreach( $answer_ratios as $ratio )
                {
                    $id = $this->get_user_id($ratio['userid']);
                    $meta['participants'][] = array( "id" => $id, "share" => $ratio['count'], "role" => "QA thread answerer" );
                }
            }

            $bonus_percentage = qa_opt('mobbr_support_selected_answer_bonus');
            if ( ! ( empty( $bonus_percentage ) || empty( $answerer ) ) )
            {
                $id = $this->get_user_id($answerer);
                $meta['participants'][] = array(
                    "id" => $id,
                    "role" => "QA thread solver",
                    "share" =>  $bonus_percentage . "%" );
            }

            // add platform owner
            $platform_percentage = qa_opt('mobbr_support_platform_percentage');
            $platform_account = qa_opt('mobbr_support_platform_account');
            if ( ! ( empty( $platform_account ) || empty( $platform_percentage ) ) )
            {
                $meta['participants'][] = array(
                    "id" => $platform_account,
                    "role" => "QA platform owner",
                    "share" =>  $platform_percentage . "%" );
            }

            return $meta;
        }

        // --------------------------------------------------------------------

        function person_meta($username)
        {
            return array(
                "id-base" => $this->id_base(),
                "message"  => "Donation to this member. The member will receive a notification mail.",
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
                "language" => qa_opt('mobbr_support_language'),
                "message" => "This payment will be instantly divided among top community members based on the points they earned.",
                "keywords" => array("qa", "question2answer"));
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
            require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-widget-button.php';
            require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-queries.php';

            $environment = qa_opt('mobbr_support_environment');
            if ($environment === 'test' )
            {
                $this->output('<script type="text/javascript" src="https://test-www.mobbr.com/mobbr.js"></script>');
                $this->output('<script>');
                $this->output('mobbr.setUiUrl("https://test-www.mobbr.com/");');
                $this->output('mobbr.setApiUrl("https://test-api.mobbr.com/");');
                $this->output('mobbr.setLightboxUrl("https://test-www.mobbr.com/lightbox/#");');
                $this->output('mobbr.createDiv();');
                $this->output('</script>');
            }
            else
            {
                $this->output('<script type="text/javascript" src="https://mobbr.com/mobbr-button.js"></script>');
            }
            if (defined('QA_MOBBR_SSO') && QA_MOBBR_SSO)
            {
                $this->output('<script>mobbrSSO.enable();</script>');
            }
            qa_html_theme_base::head_links();
            $meta = $this->get_meta();
            if (!empty( $meta ) )
            {
                $this->output('<meta name="participation" content=\''.json_encode($meta).'\'/>');
            }
        }

        // --------------------------------------------------------------------

        public function get_meta()
        {
            // -----------------------------------------------------------
            // We detect on which page type we are and generate the script
            // that is needed for that particular page type.
            // -----------------------------------------------------------

            //require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-widget-button.php';
            //require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-queries.php';

            $page = qa_request_part(0);
            $sub = qa_request_part(1);
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
                return $meta;
                //$this->output('<meta name="participation" content=\''.json_encode($meta).'\'/>');
            }
        }

        // Q2A layer function

        public function q_item_main($q_item)
        {
            require_once QA_HTML_THEME_LAYER_DIRECTORY . 'qa-mobbr-frontend.php';

            if (qa_opt('mobbr_support_show_button_in_lists') && isset($q_item['raw']['postid']) && isset($q_item['raw']['title'])) {
                $this->output(qa_mobbr_frontend::get_html_button($q_item['raw']['postid'], $q_item['raw']['title'] ));
            }
            parent::q_item_main($q_item);
        }

	}


/*
	Omit PHP closing tag to help avoid accidental output
*/
