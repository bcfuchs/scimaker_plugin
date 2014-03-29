jQuery(document).ready(function($) {
					// update handler
		var aRtP = function(buttonSelector, params) {
			var el = $(buttonSelector);
			var updater = function(response) {
				
				// here we'd update the display
				console.log('Got this from the server: '+ response);
				$(el).css({
					'background-color' : "green"
				});
				$(el).html("Added this resource!");
			}
			var onClick = function(el2) {
				$(el2).css({
					'background-color' : "blue"
				});
				$(el2).html("Adding this resource...");
			}
			$(el).click( function() {
				onClick(el);
				addResourceToProject(params.pid, params.rid, updater);
			});

			function addResourceToProject(pid, rid, handler) {
				// TODO add a token
				var data = {
					action : scimaker_addresources.action,
					project_id : pid,
					resource_id : rid,
					wp: '5eb63bbbe01eeed093cb22bb8f5acdc3'
				};
				console.log('sent request to '+scimaker_addresources.ajax_url);
				console.log(scimaker_addresources);

				// since 2.8 ajaxurl is always defined in the admin
				// header and points to
				// admin-ajax.php

				$.post(scimaker_addresources.url, data, handler);
			}
		} // aRtP
		
		$('#wp-toolbar ul#wp-admin-bar-top-secondary')
				.append('<li id="tester1" style="width: 64%;background-color:gray;color:white;border:  dotted blue;cursor:pointer;">add to project test</li>');
		aRtP('#tester1', {pid:105,rid:91});
});	
