
jQuery(document).ready(function($) {

	// update handler
	var jointeam_f = function(buttonSelector, params) {
		var el = $(buttonSelector);
		var updater = function(response) {

			// here we'd update the display
			console.log('Got this from the server: ' + response);
			$(el).css({
				'color' : "green"
			});
			$(el).html('<div class="dashicons dashicons-yes">joined!</div>');
			// location.reload();
		}
		var onClick = function(el2) {
			$(el2).css({
				'color' : "aqua"
			});
			$(el2).html('<div class="dashicons dashicons-update"></div>');
		}
		$(el).click(function() {
			onClick(el);
			jointeam(params.teamid, updater);
		});

		function jointeam(teamid, handler) {
			// TODO add a token
			var data = {
				action : scimaker_jointeam.action,
				team_id : teamid,
				wp : '5eb63bbbe01eeed093cb22bb8f5acdc3'
			};
			console.log('sent request to ' + scimaker_jointeam.url);
			console.log(scimaker_jointeam);

			// since 2.8 ajaxurl is always defined in the admin
			// header and points to
			// admin-ajax.php

			$.post(scimaker_jointeam.url, data, handler);
		}
	} // jointeam_f
	var team_id = scimaker_jointeam.team_id; // for testing purposes

	var id = 'jointeambutton';
	console.log('join team');
	console.log(scimaker_jointeam);

	var button = '<button id="'+id+'">join team</button>';
	$('#wpadminbar').append(button);
	jointeam_f('#' + id, {
		teamid : team_id
	});

});
