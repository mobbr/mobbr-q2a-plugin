<?php

	class qa_mobbr_event_handler {

		function process_event($event, $userid, $handle, $cookieid, $params)
		{
		    switch( $event )
            {
                // ------------------------------------------------------------
                // When a question is selected, we notify the Mobbr API.
                // It could be there is a pledge made which can now be resolved
                // Won't work if there is any caching.
                // ------------------------------------------------------------

                case 'a_select':
                    // $post = $params['postid'];
                    // call the Mobbr API
                    // reconstruct current URL, Mobbr will find the canonical URL there
                    $url = rtrim( qa_opt('site_url'), '/' ) . '/' . implode( "/", qa_request_parts() ) ;
                    $environment = qa_opt('mobbr_support_environment');
                    $ch = curl_init( $environment === 'test' ? 'https://test-api.mobbr.com/api/claim/claim_url' : 'https://api.mobbr.com/api/claim/claim_url' );
                    if ( $ch == FALSE )
                    {
                        error_log( "Can't open Mobbr-API: " . $url );
                        break;
                    }
                    curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
                    curl_setopt( $ch, CURLOPT_PORT, 443 );
                    curl_setopt( $ch, CURLOPT_POST, 1 );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, "url=" . urlencode( $url ) );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
                    curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
                    curl_setopt( $ch, CURLOPT_HEADER, 1 );
                    curl_setopt( $ch, CURLINFO_HEADER_OUT, 1 );
                    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 3 );
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) );
                    $res = curl_exec( $ch );
                    $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                    if ( ! ( $httpcode == 200 || $httpcode == 201 ) )
                    {
                        error_log( "HTTP-CODE $httpcode returned by Mobbr-API: " . print_r( $res, TRUE ) );
                        break;
                    }
                    if ( $res === FALSE )
                    {
                        error_log( "Can't call Mobbr-API: " . curl_error( $ch )  );
                        curl_close( $ch );
                        break;
                    }
                    curl_close( $ch );
                    break;

                default:
                    break;
            }
		}
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/