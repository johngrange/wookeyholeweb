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

define('channel', [ 'extlycore' ], function(Core) {
	"use strict";

	/* BEGIN - variables to be inserted here */


	/* END - variables to be inserted here */
	
	var $adminForm = jQuery('#adminForm'); 

	(new ChannelView({
		el : $adminForm,
		collection : new Channels()
	})).onChangeChannelType();

	var twValidationView = new TwValidationView({
		el : $adminForm,
		collection : new TwValidations()
	});

	var liValidationView = new LiValidationView({
		el : $adminForm,
		collection : new LiValidations()
	});
	
	var liOAuth2ValidationView = new LiOAuth2ValidationView({
		el : $adminForm,
		collection : new LiOAuth2Validations()
	});	

	var eventsDispatcher = _.clone(Backbone.Events);

	var fbValidationView = new FbValidationView({
		el : $adminForm,
		collection : new FbValidations(),
		attributes : {dispatcher : eventsDispatcher}
	});

	var fbChannelView = new FbChannelView({
		el : $adminForm,
		collection : new FbChannels(),
		attributes : {
			dispatcher : eventsDispatcher,
			messagesview : fbValidationView
		}
	});

	var fbAlbumView = new FbAlbumView({
		el : $adminForm,
		collection : new FbAlbums(),
		attributes : {
			fbChannelView : fbChannelView
		}
	});

	var fbChValidationView = new FbChValidationView({
		el : $adminForm,
		collection : new FbChValidations()
	});

	var fbExtendView = new FbExtendView({
		el : $adminForm,
		collection : new FbExtends(),
		attributes : {dispatcher : eventsDispatcher}
	});

	var gplusValidationView = new GplusValidationView({
		el : $adminForm,
		collection : new GplusValidations()
	});

	var liGroupView = new LiGroupView({
		el : $adminForm,
		collection : new LiGroups()
	});

	var liCompanyView = new LiCompanyView({
		el : $adminForm,
		collection : new LiCompanies()
	});
	
	var liOAuth2CompanyView = new LiOAuth2CompanyView({
		el : $adminForm,
		collection : new LiOAuth2Companies()
	});	

	var vkValidationView = new VkValidationView({
		el : $adminForm,
		collection : new VkValidations()
	});

	var vkGroupView = new VkGroupView({
		el : $adminForm,
		collection : new VkGroups()
	});

	var scoopitValidationView = new ScoopitValidationView({
		el : $adminForm,
		collection : new ScoopitValidations()
	});

	var scoopitTopicView = new ScoopitTopicView({
		el : $adminForm,
		collection : new ScoopitTopics()
	});

	var tumblrValidationView = new TumblrValidationView({
		el : $adminForm,
		collection : new TumblrValidations()
	});

	var bloggerValidationView = new BloggerValidationView({
		el : $adminForm,
		collection : new BloggerValidations()
	});

	var xingValidationView = new XingValidationView({
		el : $adminForm,
		collection : new XingValidations()
	});

	window.xtAppDispatcher = eventsDispatcher;

});
