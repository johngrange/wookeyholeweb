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

var ScoopitValidationView = Backbone.View.extend({
	events : {
		'click #scoopitvalidationbutton' : 'onValidationReq'
	},

	initialize : function() {
		this.collection.on('add', this.loadvalidation, this);
	},

	onValidationReq : function onValidationReq() {
		var view = this,

			channel_id = view.$('#channel_id').val().trim(),
			consumer_key = view.$('#consumer_key').val().trim(),
			consumer_secret = view.$('#consumer_secret').val().trim(),
			access_token = view.$('#access_token').val().trim(),
			access_secret = view.$('#access_secret').val().trim(),

			token = view.$('#XTtoken').attr('name');

		view.$('#channel_id').val(channel_id);
		view.$('#consumer_key').val(consumer_key);
		view.$('#consumer_secret').val(consumer_secret);
		view.$('#access_token').val(access_token);
		view.$('#access_secret').val(access_secret);

		view.$(".loaderspinner").addClass('loading');

		this.collection.create(this.collection.model, {
			attrs : {
				channel_id : channel_id,
				consumer_key : consumer_key,
				consumer_secret : consumer_secret,
				access_token : access_token,
				access_secret : access_secret,
				token : token
			},

			wait : true,
			dataType:     'text',
			error : function(model, fail, xhr) {
				view.$(".loaderspinner").removeClass('loading');
				validationHelper.showError(view, fail.responseText);
			}
		});
	},

	loadvalidation : function loadvalidation(resp) {
		var status = resp.get('status'),
			error_message = resp.get('error_message'),
			user = resp.get('user'),
			icon = resp.get('icon'),
			url = resp.get('url');

		this.$(".loaderspinner").removeClass('loading');

		if (status) {
			validationHelper.showSuccess(this, user.id_str, icon, url);
		} else {
			validationHelper.showError(this, error_message);
		}
	}

});
