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

var TumblrValidationView = Backbone.View.extend({
	events : {
		'click #tumblrvalidationbutton' : 'onValidationReq'
	},

	initialize : function() {
		this.collection.on('add', this.loadvalidation, this);
		this.bloglist = '#blogs';
	},

	onValidationReq : function onValidationReq() {
		var view = this,
			channel_id = view.$('#channel_id').val().trim(),
			token = view.$('#XTtoken').attr('name'),
			list = this.$(this.bloglist);

		view.$('#channel_id').val(channel_id);

		view.$(".loaderspinner").addClass('loading');
		Core.UiHelper.listReset(list);

		this.collection.create(this.collection.model, {
			attrs : {
				channel_id : channel_id,
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
			url = resp.get('url'),
			list = this.$(this.bloglist);

		this.$(".loaderspinner").removeClass('loading');

		if (status) {
			list.empty();
			_.each(user.blogs, function(item) {
				var opt = new Option();
				opt.value = item.id;
				opt.text = item.name;
				list.append(opt);
			});
			list.trigger('liszt:updated');

			validationHelper.showSuccess(this, user.name, icon, url);
		} else {
			validationHelper.showError(this, error_message);
		}
	}

});
