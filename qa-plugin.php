<?php

/*
	Plugin Name: Mobbr Support
	Plugin URI: https://github.com/mobbr/mobbr-q2a-plugin
	Plugin Description: Add MOBBR.COM crowdpayment support, adds crowdfunding and crowdpaying to questions
	Plugin Version: 1.1
	Plugin Date: 2012-08-21
	Plugin Author: Mobbr
	Plugin Author URI: https://mobbr.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI:
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_layer('qa-mobbr-layer.php', 'Mobbr metadata');
	qa_register_plugin_module('page', 'qa-mobbr-resolver.php', 'qa_mobbr_resolver', 'Mobbr name resolver');
    qa_register_plugin_module('widget', 'qa-mobbr-widget-button.php', 'qa_mobbr_widget_button', 'Mobbr buttons');
    //qa_register_plugin_module('widget', 'qa-mobbr-widget-badge.php', 'qa_mobbr_widget_badge', 'Mobbr badges');
    //qa_register_plugin_module('widget', 'qa-mobbr-widget-header.php', 'qa_mobbr_widget_header', 'Mobbr header');
    qa_register_plugin_module('module', 'qa-mobbr-admin.php', 'qa_mobbr_admin', 'Mobbr admin');
    qa_register_plugin_module('event', 'qa-mobbr-event-handler.php', 'qa_mobbr_event_handler', 'Mobbr notifier');


/*
	Omit PHP closing tag to help avoid accidental output
*/