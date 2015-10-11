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

var ScoopitTopicView = Backbone.View
		.extend({

			events : {
				'click #submit_topic_search' : 'onTopicsSearch',
				'change #topic_select_id' : 'onChangeTopic'
			},

			initialize : function() {
				this.collection.on('add', this.load, this);
				this.topiclist = '#topic_select_id';
			},

			onTopicsSearch : function onTopicsSearch() {
				var thisView = this,
					list = thisView.$(this.topiclist),
					channelId = thisView.$('#channel_id').val(),
					channelToken = thisView.$('#XTtoken').attr('name'),
					search_topic = thisView.$('#search_topic').val();

				Core.UiHelper.listReset(list);

				this.collection.create(this.collection.model, {
					attrs : {
						channel_id : channelId,
						token : channelToken,
						search : search_topic
					},

					wait : true,
					dataType:     'text',
					error : function(model, fail, xhr) {
						validationHelper.showError(thisView,
								fail.responseText);
					}
				});
			},

			load : function load(message) {
				var thisView = this, topiclist = this.$(this.topiclist), items;

				topiclist.empty();
				if (message.get('status')) {
					items = message.get('topics');
					_.each(items, function(item) {
						var opt = new Option();
						opt.value = item.id;
						opt.text = item.name;
						topiclist.append(opt);
					});
					topiclist.trigger('liszt:updated');
				} else {
					validationHelper.showError(thisView,
							message.get('error_message'));
				}

			},

			onChangeTopic : function() {
				var topic_id = this.$('#topic_select_id').val();
				this.$('#topic_id').val(topic_id);
			}

		});
