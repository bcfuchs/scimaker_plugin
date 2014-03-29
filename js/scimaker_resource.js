jQuery(document).ready(function($) {
	console.log('okeydoke');
					// update handler
		var aRtP = function(buttonSelector, params) {
			var el = $(buttonSelector);
			var updater = function(response) {
				
				// here we'd update the display
				console.log('Got this from the server: '+ response);
				$(el).css({
					'color' : "green"
				});
				$(el).html('<div class="dashicons dashicons-yes"></div>');
				location.reload();
			}
			var onClick = function(el2) {
				$(el2).css({
					'color' : "aqua"
				});
				$(el2).html('<div class="dashicons dashicons-update"></div>');
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
		
//		$('#wp-toolbar ul#wp-admin-bar-top-secondary')
//				.append('<li id="tester1" style="width: 64%;color:white;cursor:pointer;">add to project test</li>');
		$('ul.scimaker_resources_list li').each(function(i,v){
			var d= new Date();
			var id = "tester_"+d.getTime()+"_"+i;
			
			
				
			var resource_id; 
			resource_id = $(v).attr('data-scimaker-id');
			var project_id;
			project_id =scimaker_addresources.project_id; 
			if (scimaker_addresources.resources.indexOf(resource_id) < 0) {
				$(v).prepend('<span id="'+id+'" style="width: 64%;color:white;cursor:pointer;font-weight:lighter;font-size:8pt;"> <div class="dashicons dashicons-plus"></div></span>');
				console.log('in ul');
				aRtP('#'+id, {pid:project_id,rid:resource_id});
			}
			else {
//TODO this should be a minus !
				$(v).prepend('<span id="'+id+'" style="width: 64%;color:green;cursor:pointer;font-weight:lighter;font-size:8pt;"> <div class="dashicons dashicons-yes"></div></span>');
			}
			console.log("project id: "+project_id);
			console.log(scimaker_addresources.resources);
			//TODO -- update any lists on page.
			//TODO an add button should appear only on resources not yet in this project.
			//TODO need to pass in list of resources in this project
		});
		
		
});	
