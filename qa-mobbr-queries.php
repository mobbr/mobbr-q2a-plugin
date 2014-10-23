<?php

    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    function qa_db_mobbr_decay_query($percentage)
    {
        // called by cronjob, just decays everyone's point with a configurable percentage
        return array(
            'source' => "UPDATE ^userpoints SET points=GREATEST(100, points*(100-#)/100)",
            'arguments' => array($percentage),
        );
    }

    function qa_db_mobbr_question_ratio_query($postid)
    {
        /*
            Derived from:

            SELECT u.handle, u.userid, GREATEST( COUNT(v.postid), 0 ) AS numvotes
            FROM qa_posts AS q
            JOIN qa_posts AS a ON q.postid=a.parentid
            JOIN qa_uservotes AS v ON a.postid=v.postid
            JOIN qa_users AS u ON u.userid=a.userid
            WHERE q.postid=6 AND q.parentid IS NULL
            GROUP BY u.userid
            HAVING numvotes > 0
         */

        // ---------------------------------------------------------------------------
        // Return the ratio between ANSWERS ONLY.
        // ---------------------------------------------------------------------------

        if ((defined('QA_EXTERNAL_USERS') && QA_EXTERNAL_USERS) || defined('QA_WORDPRESS_INTEGRATE_PATH'))
        {
            return array(
                'columns' => array('a.userid', 'count' => 'GREATEST( COUNT(v.postid), 0 )'),
                'source' => "^posts AS q
                    JOIN ^posts AS a ON q.postid=a.parentid
                    JOIN ^uservotes AS v ON a.postid=v.postid
                    WHERE q.postid=#  AND q.parentid IS NULL AND NOT a.userid IS NULL
                    GROUP BY a.userid
                    HAVING count > 0",
                'arguments' => array($postid),
                'sortdesc' => 'count',
            );
        }
        else
        {
            return array(
                'columns' => array('handle AS userid', 'count' => 'GREATEST( COUNT(v.postid), 0 )'),
                'source' => "^posts AS q
                    JOIN ^posts AS a ON q.postid=a.parentid
                    JOIN ^uservotes AS v ON a.postid=v.postid
                    JOIN ^users AS u ON u.userid=a.userid
                    WHERE q.postid=# AND q.parentid IS NULL AND NOT u.userid IS NULL
                    GROUP BY u.userid
                    HAVING count > 0",
                'arguments' => array($postid),
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
            'columns' => array('type' => "IF( selchildid IS NULL AND parentid IS NULL, 'pledge', 'payment' )"),
            'source' => "^posts WHERE postid=#",
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
