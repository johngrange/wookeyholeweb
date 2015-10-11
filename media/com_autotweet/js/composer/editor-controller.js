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

/* global define */
'use strict';

define('editor-controller', [ 'extlycore' ], function(Core) {

	// Do this instead
	var controller = function($scope, $rootScope, $sce, RequestService) {
		var _this = this;
		var local = $scope.editorCtrl;
		var Request = RequestService;
		var theForm = jQuery('.extly-body form');
		var option_filter = null;
		var redirectOnSuccess = false;

		local.waiting = false;
		local.remainingCount = 0;
		local.showDialog = false;

		_this.addRequest = function(e) {
			var descr = (local.description || ''), request;
			var form, params = {}, attrs = {}, agenda = [], channels = [];

			e.preventDefault();

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			descr = descr.trim();

			// No mensage
			if (descr.length == 0) {
				local.showDialog = true;
				local.messageResult = false;
				local.messageText = $sce.trustAsHtml('Invalid field: Message');

				return;
			}

			params['description'] = descr;
			params['url'] = local.url;

			form = theForm.serializeArray();
			_.each(form, function(item) {
				if (item.name == 'postThis') {
					attrs['postthis'] = item.value;
				} else if (item.name == 'everGreen') {
					attrs['evergreen'] = item.value;
				} else if (item.name == 'channelchooser[]') {
					channels.push(item.value);
				} else if (item.name == 'unix_mhdmd') {
					attrs[item.name] = item.value;
				} else if (item.name == 'repeat_until') {
					attrs[item.name] = item.value;
				} else if (item.name == 'ref_id') {
					params[item.name] = item.value;
					attrs[item.name] = item.value;
				} else if (item.name == 'agenda[]') {
					agenda.push(item.value);
				} else if (item.name.match(/unix_mhdmd_/)
						|| (item.name == 'scheduling_date')
						|| (item.name == 'scheduling_time')
						|| (item.name  == 'postAttrs')
						|| (item.name  == 'selectedMenuItem')) {
					// Skip
				} else {
					/*
					 * option
					 * view
					 * task
					 * returnurl
					 * b29aa44adfb8707f3abd0222bfb52329
					 * lang
					 * published
					 * id
					 * image_url
					 */
					params[item.name] = item.value;
				}
			});

			if (local.option_filter) {
				attrs['option_filter'] = local.option_filter;
			}

			// autotweet_advanced_attrs
			attrs['agenda'] = agenda;
			attrs['channels'] = channels;
			attrs['channels_text'] = _this.getChannelsText();
			attrs['image'] = '';
			params['autotweet_advanced_attrs'] = JSON.stringify(attrs);

			if (local.plugin == 'autotweetpost') {
				params['taskCommand'] = 'applyAjaxOwnAction';
			} else {
				params['taskCommand'] = 'applyAjaxPluginAction';
			}

			params['ajax'] = 1;

			if ((attrs['unix_mhdmd']) && (attrs['unix_mhdmd'].length > 0) && (agenda.length > 1)) {
				_this.error({
					status : '',
					statusText : 'Repeat Expression not allowed for more than one Agenda date.'
				});

				return false;
			}

			if ( (agenda.length > 0) && (_this.hasPastDates(agenda)) )
			{
				_this.error({
					status : '',
					statusText : 'Agenda has dates in the past. Please, remove them.'
				});

				return false;
			}

			local.waiting = true;
			request = new Request(params);
			request.$save(null, _this.success, _this.error);
		};

		_this.jQueryAddRequest = function(event) {
			_this.loadReqId = null;
			_this.addRequest(event);
			$scope.$digest();
		};

		_this.jQueryAddRequestLoad = function(event) {
			_this.loadReqId = local.request_id;
			_this.addRequest(event);
			$scope.$digest();
		};

		_this.jQueryAddRequestAndRedirect = function(event) {
			_this.redirectOnSuccess = true;
			_this.jQueryAddRequest(event);
		};

		_this.getChannelsText = function() {
			var options = jQuery('#channelchooser option:selected'), channels_text;

			channels_text = _.reduce(options, function(memo, option) {
				var txt = jQuery(option).text();

				if (memo == '') {
					return txt;
				} else {
					return memo + ', ' + txt;
				}
			}, '');

			return channels_text;
		};

		_this.hasPastDates = function (agenda) {
			var today = new Date(),
			todayUtc = today.getTime() + (today.getTimezoneOffset() * 60000),
			isPastDate = false,
			offset =  parseInt(jQuery('#Timezone_Offset').val()),
			offsetSign = Math.sign(offset),
			offsetH = Math.abs(offset) / 3600,
			offsetM = Math.abs(offset) % 3600;

			try {
				_.each(agenda, function(day) {
					var d, dUtc, dateString = day;
					
					// 2015-06-09 0:42
					if (day.match(/^\d\d\d\d-\d\d-\d\d \d:\d\d/)) {
						day = day.replace(' ', ' 0');
					};					
	
					// 2015-06-09 00:42 - Chrome
					if (day.match(/^\d\d\d\d-\d\d-\d\d \d\d:\d\d$/)) {
						dateString = day.replace(' ', 'T') + ':00';
						
					// 2015-06-09 00:42:00 - Firefox
					} else if (day.match(/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/)) {
						dateString = day.replace(' ', 'T');
					};
	
					if (offsetSign >= 0) {
						dateString = dateString + '+';
					} else {
						dateString = dateString + '-';
					}
	
					dateString = dateString + ('0' +offsetH).slice(-2) + ':' + ('0' +offsetM).slice(-2);
	
					d = new Date(dateString);
					dUtc = d.getTime() + (d.getTimezoneOffset() * 60000);
	
					if (dUtc < todayUtc) {
						isPastDate = true;
					};
				});
			} catch (e) {
				isPastDate = true;
			}
	
			return isPastDate;
		};

		local.countRemaining = function() {
			var style, c = local.description.length;

			if (!_.isEmpty(local.hashtags))	{
				c = c + 1 + local.hashtags.length;
			}

			style = 'label';

			if ((c > 0) && (c <= 60)) {
				style = 'label label-success';
			} else if ((c > 60) && (c <= 100)) {
				style = 'label label-warning';
			} else if (c > 100) {
				style = 'label label-important';
			}

			local.remainingCount = c;
			local.remainingCountClass = style;
		};

		local.menuitemlistHide = function() {
			var el = document.getElementById('menulist_group'),
				menuitemlist = angular.element(el);

			if (menuitemlist.hasClass('hide')) {
				menuitemlist.removeClass('hide');
			} else {
				menuitemlist.addClass('hide');
			};
		};

		_this.success = function(response) {
			local.showDialog = true;
			local.messageResult = response.status;
			local.messageText = $sce.trustAsHtml(response.message);

			local.request_id = 0;
			local.ref_id = response.hash;
			local.waiting = false;

			if ((response.status) && (_this.redirectOnSuccess)) {
				Joomla.submitbutton('cancel');
			};

			_this.redirectOnSuccess = false;

			if (_this.loadReqId) {
				$rootScope.$emit('editRequest', _this.loadReqId);
				_this.loadReqId = null;
			} else {
				_this.reset();
			}

			$rootScope.$emit('newRequest');
		};

		_this.load = function(request) {
			_this.reset();
			local.waiting = false;

			local.showDialog = false;
			local.messageResult = 'success';
			local.messageText = $sce.trustAsHtml('-Loaded-');

			// Ng-model
			local.description = request.description;
			local.url = request.url;
			local.hashtags = request.xtform.hashtags;

			// Ng-value
			local.request_id = request.id;
			local.ref_id = request.ref_id;
			local.plugin = request.plugin;

			// Ng-model - agendasCtrl // agendasCtrl.scheduling_date / agendasCtrl.scheduling_time
			if (request.autotweet_advanced_attrs) {
				$rootScope.$emit('loadAgenda', request.autotweet_advanced_attrs.agenda);
			}

			// Rest of the fields
			jQuery('#image_url').val(request.image_url);
			jRefreshPreview('', 'image_url');

			if (request.autotweet_advanced_attrs) {
				local.fulltext = request.autotweet_advanced_attrs.fulltext;
				jQuery('#itemeditor_postthis').val(request.autotweet_advanced_attrs.postthis);
				jQuery('#itemeditor_evergreen').val(request.autotweet_advanced_attrs.evergreen);
				jQuery('#channelchooser')
					.val(request.autotweet_advanced_attrs.channels)
					.trigger('liszt:updated');
				jQuery('#unix_mhdmd').val(request.autotweet_advanced_attrs.unix_mhdmd);
				jQuery('#repeat_until').val(request.autotweet_advanced_attrs.repeat_until);
				local.option_filter = request.autotweet_advanced_attrs.option;

				Core.UiHelper.resetBtnGroup('#itemeditor_postthis');
				Core.UiHelper.resetBtnGroup('#itemeditor_evergreen');
			}

			local.countRemaining();
		};

		_this.getHash = function() {
			var now = new Date(), hash;

			hash = CryptoJS.MD5('' + now.getTime() + _.random(0, 9007199254740992));
			hash = CryptoJS.MD5(hash + _.random(0, 9007199254740992));
			hash = CryptoJS.MD5(hash + _.random(0, 9007199254740992));

			return hash;
		};

		_this.reset = function() {
			local.ref_id = _this.getHash();

			local.plugin = 'autotweetpost';
			local.description = '';
			local.url = '';
			local.selectedMenuItem = '';

			local.fulltext = '';
			local.hashtags = '';

			jQuery('#image_url').val('');
			jRefreshPreview('', 'image_url');
			jQuery('#image_url-image').html('');

			// Yes
			jQuery('#itemeditor_postthis').val(1);
			Core.UiHelper.resetBtnGroup('#itemeditor_postthis');

			// No
			jQuery('#itemeditor_evergreen').val(2);
			Core.UiHelper.resetBtnGroup('#itemeditor_evergreen');

			jQuery('#channelchooser')
				.val([])
				.trigger('liszt:updated');

			jQuery('.cronjob-expression-form select').val('*');
			$rootScope.$emit('loadAgenda', []);

			jQuery('#unix_mhdmd').val('');
			jQuery('#repeat_until').val('');

			local.countRemaining();
		};

		_this.jQueryReset = function(event) {
			event.preventDefault();
			_this.reset();
			$scope.$digest();
		};

		_this.error = function(httpResponse) {
			local.showDialog = true;
			local.messageResult = false;
			local.messageText = $sce.trustAsHtml('Error: ' + httpResponse.status + ' ' + httpResponse.statusText);

			_this.redirectOnSuccess = false;
			local.waiting = false;
		};

		_this.editRequest = function(e, request_id) {
			var request, params = {};

			e.preventDefault();

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			if (!request_id) {
				return;
			}

			params['id'] = request_id;
			params['request_id'] = request_id;
			params['taskCommand'] = 'readAjaxAction';
			params['ajax'] = 1;

			local.waiting = true;
			request = new Request(params);
			request.$get(null, _this.load, _this.error);
		};

		_this.publishRequest = function(e, request_id) {
			var request, params = {};

			e.preventDefault();

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			if (!request_id) {
				return;
			}

			params['id'] = request_id;
			params['request_id'] = request_id;
			params['taskCommand'] = 'publishAjaxAction';
			params['ajax'] = 1;

			local.waiting = true;
			request = new Request(params);
			request.$save(null, _this.success, _this.error);
		};

		_this.cancelRequest = function(e, request_id) {
			var request, params = {};

			e.preventDefault();

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			if (!request_id) {
				return;
			}

			params['id'] = request_id;
			params['request_id'] = request_id;
			params['taskCommand'] = 'cancelAjaxAction';
			params['ajax'] = 1;

			local.waiting = true;
			request = new Request(params);
			request.$save(null, _this.success, _this.error);
		};

		_this.backtoQueueRequest = function(event, request_id) {
			var request, params = {};

			e.preventDefault();

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			if (!request_id) {
				return;
			}

			params['id'] = request_id;
			params['request_id'] = request_id;
			params['taskCommand'] = 'backtoQueueAjaxAction';
			params['ajax'] = 1;

			local.waiting = true;
			request = new Request(params);
			request.$save(null, _this.success, _this.error);
		};

		_this.loadUrl = function(itemId) {
			var request, params = {};

			// Operation in progress, go home
			if (local.waiting) {
				return;
			}

			params['itemId'] = itemId;
			params['taskCommand'] = 'routeAjaxItemId';
			params['ajax'] = 1;

			local.waiting = true;
			request = new Request(params);
			request.$save(null, _this.successRoute, _this.error);
		};

		_this.successRoute = function(response) {
			local.url = response.url;
			local.waiting = false;
		};

		$rootScope.$on('editRequest', _this.editRequest);
		$rootScope.$on('publishRequest', _this.publishRequest);
		$rootScope.$on('cancelRequest', _this.cancelRequest);
		$rootScope.$on('backtoQueueRequest', _this.backtoQueueRequest);

		// Joomla 3 - Back-end Site
		jQuery('#toolbar-apply button').attr('onclick', null).click(_this.jQueryAddRequestLoad);
		jQuery('#toolbar-save button').attr('onclick', null).click(_this.jQueryAddRequestAndRedirect);
		jQuery('#toolbar-save-new button').attr('onclick', null).click(_this.jQueryAddRequest);

		// Joomla 3 - Front-end Site
		// apply
		jQuery('#F0FHeaderHolder button:nth-of-type(1)').attr('onclick', null).click(_this.jQueryAddRequestLoad);

		// save
		jQuery('#F0FHeaderHolder button:nth-of-type(2)').attr('onclick', null).click(_this.jQueryAddRequestAndRedirect);

		// savenew
		jQuery('#F0FHeaderHolder button:nth-of-type(3)').attr('onclick', null).click(_this.jQueryAddRequest);

		// Joomla 2.5 - Back-end Site and Front-end Site
		jQuery('#toolbar-apply a').attr('onclick', null).click(_this.jQueryAddRequestLoad);
		jQuery('#toolbar-save a').attr('onclick', null).click(_this.jQueryAddRequestAndRedirect);
		jQuery('#toolbar-save-new a').attr('onclick', null).click(_this.jQueryAddRequest);
	};

	controller.$inject = ['$scope', '$rootScope', '$sce', 'Request'];

	return controller;

});
