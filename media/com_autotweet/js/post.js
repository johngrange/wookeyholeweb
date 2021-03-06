/**
 * @package Extly.Components
 * @subpackage com_autotweet - AutoTweet posts content to social channels
 *             (Twitter, Facebook, LinkedIn, etc).
 *
 * @author Prieco S.A.
 * @copyright Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link http://www.extly.com http://support.extly.com
 */

/* jslint plusplus: true, browser: true, sloppy: true */
/* global jQuery, Request, Joomla, alert, Backbone */

define('post', [], function() {
	"use strict";

	/* BEGIN - variables to be inserted here */

/**
 * @package Extly.Components
 * @subpackage com_autotweet - AutoTweet posts content to social channels
 *             (Twitter, Facebook, LinkedIn, etc).
 *
 * @author Prieco S.A.
 * @copyright Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link http://www.extly.com http://support.extly.com
 */

/* jslint plusplus: true, browser: true, sloppy: true */
/* global jQuery, Request, Joomla, alert, Backbone */

var PostView = Backbone.View.extend({

	events : {
		'change #plugin' : 'onChangePlugin'
	},

	initialize : function() {
		this.overrideConditionsTab = this.$('#overrideconditions-tab');
		this.auditInfoTab = this.$('#auditinfo-tab');

		// Activate Tabs
		this.$('#qTypeTabs a[data-toggle=tab]').first().tab();

		this.onChangePlugin();
		// this.onChangeCreateEvent();
	},

	onChangePlugin: function() {
		var plugin = this.$('#plugin').val();

		if (plugin == 'autotweetpost')
		{
			this.overrideConditionsTab.fadeIn(0);
			this.overrideConditionsTab.find('a').tab('show');

			jQuery('<style>')
	    		.prop('type', 'text/css')
	    		.html('#autotweet-advanced-text-attrs {display: none;}')
	    		.appendTo('head');
		}
		else
		{
			this.overrideConditionsTab.fadeOut(0);
			this.auditInfoTab.find('a').tab('show');
		}
	}

});
	/* END - variables to be inserted here */

	// Image
	window.jInsertFieldValue = function jInsertFieldValue(value, id) {
		jQuery('#' + id).val(value);
	};

	// User
	window.jSelectUser_author = function jSelectUser_author(id, username) {
		jQuery('#author').val(id);
		jQuery('#author_username').val(username);
		try {
			jQuery('#sbox-window').close();
		} catch (err) {
			SqueezeBox.close();
		}
	};

	var postView = new PostView({
		el : jQuery('#adminForm')
	});

	return postView;

});