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

define('liveupdate', [ 'extlycore' ],
		function(Core) {
	"use strict";

	var liveUpdateView = null, updateNotice;

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

var Update = Core.ExtlyModel.extend({
	url : function() {
		return Core.SefHelper.route('index.php?option=com_autotweet&view=cpanels&task=getUpdateInfo');
	}
});/**
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

var Updates = Backbone.Collection.extend({
	model : Update
});
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

var LiveUpdateView = Backbone.View.extend({

	initialize : function() {
		var view = this;

		this.loadStats();

		this.$updateNotice = this.$("#updateNotice");
		this.collection.on('add', this.loadLiveUpdates, this);

		this.$el.ajaxStart(function() {
			view.$(".loaderspinner72").addClass('loading72');
		}).ajaxStop(function() {
			view.$(".loaderspinner72").removeClass('loading72');
		});

		this.getLiveUpdates();
	},

	loadStats : function() {
		if ((nv) && (requestsData)) {
			nv.addGraph(function() {
				  var chart = nv.models.pieChart()
				      .x(function(d) { return d.label })
				      .y(function(d) { return d.value })
				      .showLabels(true)
				      .labelType("value");

				    d3.select("#requests-chart svg")
				        .datum(requestsData)
				        .call(chart);

				  return chart;
				});
		};

		if ((nv) && (postsData)) {
			nv.addGraph(function() {
				  var chart = nv.models.pieChart()
				      .x(function(d) { return d.label })
				      .y(function(d) { return d.value })
				      .showLabels(true)
				      .labelType("percent");

				    d3.select("#posts-chart svg")
				        .datum(postsData)
				        .call(chart);

				  return chart;
				});
		};

		if ((nv) && (timelineData)) {
			nv.addGraph(function() {
				var chart = nv.models.lineChart()
                	.margin({left: 100})
                	.useInteractiveGuideline(true)
                	.showLegend(true)
                	.showYAxis(true)
                	.showXAxis(true);

				chart.xAxis
					.axisLabel('Date')
					.tickFormat(function(d) {
				          return d3.time.format('%Y-%m-%d')(new Date(d * 1000))
				    });

				chart.yAxis
					.axisLabel('Messages')
					.tickFormat(d3.format('.f'));

				d3.select('#posts-timeline svg')
					.datum(timelineData)
					.call(chart);

				nv.utils.windowResize(function() { chart.update() });

				return chart;
			});
		};
	},

	getLiveUpdates : function() {
		var view = this;

		this.collection.create(this.collection.model, {
			attrs : {
				'token' : view.$('#XTtoken').attr('name')
			},

			wait : true,
			dataType: 'text',
			error : function(model, fail, xhr) {
				view.$updateNotice.html(fail.responseText);
			}
		});
	},

	loadLiveUpdates : function(resp) {
		var hasUpdate = resp.get('hasUpdate');

		if (hasUpdate) {
			this.$updateNotice.html(resp.get('result'));
		}
	}

});
	/* END - variables to be inserted here */

	updateNotice = jQuery('#updateNotice');

	if (updateNotice.size())
	{
		liveUpdateView = new LiveUpdateView({
			el : jQuery('#adminForm'),
			collection : new Updates()
		});
	}
	else
	{
		updateNotice = jQuery('#fullUpdateNotice');

		if (updateNotice.size())
		{
			liveUpdateView = new FullLiveUpdateView({
				el : jQuery('#adminForm'),
				collection : new Updates()
			});
		}
	}

	return liveUpdateView;

});
