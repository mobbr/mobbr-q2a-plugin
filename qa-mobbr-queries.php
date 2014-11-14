<?php

    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    function qa_db_mobbr_question_ratio_query($postid)
    {
        // ---------------------------------------------------------------------------
        // Return the ratio between ANSWERS ONLY.
        // ---------------------------------------------------------------------------

        if ((defined('QA_EXTERNAL_USERS') && QA_EXTERNAL_USERS) || defined('QA_WORDPRESS_INTEGRATE_PATH'))
        {
            return array(
                'columns' => array('userid', 'count' => 'GREATEST( SUM( netvotes ), 0 )'),
                'source' => "^posts WHERE (parentid=# OR postid=#) AND NOT userid IS NULL GROUP BY userid",
                'arguments' => array($postid, $postid),
                'sortdesc' => 'count',
            );
        }
        else
        {
            return array(
                'columns' => array('userid' => 'u.handle', 'count' => 'GREATEST( SUM( netvotes ), 0 )'),
                'source' => "^posts AS p
                    JOIN ^users AS u ON u.userid=p.userid
                    WHERE (p.parentid=# OR p.postid=#) AND NOT u.userid IS NULL GROUP BY u.userid",
                'arguments' => array($postid, $postid),
                'sortdesc' => 'count',
            );
        }
    }

    function qa_db_mobbr_question_type_query($postid)
    {
        /*
            Derived from:

            SELECT *
            FROM qa_posts AS q
            WHERE q.postid=6 AND q.parentid IS NULL
         */

        // ---------------------------------------------------------------------------
        // Return the ratio between ANSWERS ONLY. If no asnwer has been accepted yet
        // no script is returned, allowing for unclaimed donations, making it
        // a crowdfunding button.
        // ---------------------------------------------------------------------------

        return array(
            'columns' => array('type' => "IF( q.selchildid IS NULL AND q.parentid IS NULL, 'pledge', 'payment' )", 'userid' => 'a.userid'),
            'source' => "^posts AS q
                LEFT JOIN ^posts AS a ON a.postid=q.selchildid
                WHERE q.postid=#",
            'arguments' => array($postid),
        );
    }

    function qa_db_mobbr_category_ratio_query($category)
    {
        return array(
            'test' => 'category',
            'columns' => array(''),
            'source' => '',
            'arguments' => array(),
        );
    }

    function qa_db_mobbr_favorites_ratio_query($category)
    {
        return array(
            'test' => 'category',
            'columns' => array(''),
            'source' => '',
            'arguments' => array(),
        );
    }

    function qa_db_mobbr_tag_ratio_query($category)
    {
        return array(
            'test' => 'tag',
            'columns' => array(''),
            'source' => '',
            'arguments' => array(),
        );
    }


    function qa_db_mobbr_reputation_query( )
    {
        if ((defined('QA_EXTERNAL_USERS') && QA_EXTERNAL_USERS) || defined('QA_WORDPRESS_INTEGRATE_PATH'))
        {
            return array(
                'columns' => array('p.points', 'p.userid'),
                'source' => '^userpoints AS p
                    WHERE p.points>100
                    ORDER BY p.points DESC
                    LIMIT 50',
                'arguments' => array(),
            );
        }
        else
        {
            return array(
                'columns' => array('p.points', 'u.handle AS userid' ),
                'source' => '^userpoints AS p
                    JOIN ^users AS u ON u.userid=p.userid
                    WHERE p.points>100
                    ORDER BY p.points DESC
                    LIMIT 50',
                'arguments' => array(),
            );
        }
    }

/*
    Omit PHP closing tag to help avoid accidental output
*/
