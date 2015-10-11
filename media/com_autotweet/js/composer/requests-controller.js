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

define('requests-controller', ['ng-table'], function() {

	//Do this instead
	var controller = function($scope, $timeout, $rootScope, Request, ngTableParams) {
		var _this = this,
			local = $scope.requestsCtrl;

		local.firstTime = true;

		local.requestsTable = new ngTableParams({
	        count: window.xtListLimit
	    }, {
	        total: 0,

	        // Hides page sizes
	        counts: [],
	        getData: function($defer, params) {
	        	local.waiting = true;

	        	Request.query({

	        		// Controller
	    			taskCommand: 'browse',
	    			ajax: 1,

	    			// Pagination
	    			boxchecked: 0,
	    			hidemainmenu: 0,
	    			filter_order: 'publish_up',
	    			filter_order_Dir: 'ASC',
	    			limitstart: 0,
	    			limit: window.xtListLimit,

	    			// Filters
	    			publish_up: 0,
	    			search: '',
	    			plugin: 0,
	    			published: 0

	    		}, function(data) {
	                $timeout(function() {
	                	local.waiting = false;
	                	$defer.resolve(data);

	                	if (local.firstTime) {
	                		local.firstTime = false;
	                		_this.loadReqParam(data);
	                	};
	                }, 500);
	            });
	        }
	    });

		_this.loadReqParam = function(data) {
			var req_id = _this.getQueryParams('req-id');

        	// There is an Url request to select a req-id
        	if (!req_id) {
        		return false;
        	}

    		// Wait until data is loaded
        	data.$promise.then(function (data) {
        		var i = 0;

        		// Load the request in the editor
        		$rootScope.$emit('editRequest', req_id);

        		// Select it in the table
        		_.each(data, function(item) {
        			if (item.id == req_id) {
        				data[i].$selected = true;
        			};

        			i++;
        		});
        	});
		};

		local.requestsTable.doRefresh = function() {
			local.requestsTable.reload();
		};

		local.requestsTable.selectRow = function(row) {
			for (var i = 0; i < local.requestsTable.data.length; i++) {
				local.requestsTable.data[i].$selected = false;
			}
			row.$selected = true;
		};

		local.requestsTable.editRow = function(row) {
			$rootScope.$emit('editRequest', row.id);
		};

		local.requestsTable.publishRow = function(row) {
			$rootScope.$emit('publishRequest', row.id);
		};

		local.requestsTable.cancelRow = function(row) {
			$rootScope.$emit('cancelRequest', row.id);
		};

		local.requestsTable.backtoQueueRow = function(row) {
			$rootScope.$emit('backtoQueueRequest', row.id);
		};

		_this.getQueryParams = function(queryString) {
			var query = (window.location.search).substring(1);

			var obj = _.chain(query.split('&')).map(function(params) {
				var p = params.split('=');
				return [p[0], decodeURIComponent(p[1])];
			}).object().value();

			return obj[queryString];
		};

		$rootScope.$on('newRequest', local.requestsTable.doRefresh);
	};

	controller.$inject = ['$scope', '$timeout', '$rootScope', 'Request', 'ngTableParams'];

	return controller;

});
