

	function get_autocomplete_words(el) {
		var words = [];
//DONT tokenise!
		jQuery(el).find('li').each(function(i, v) {	words.push(jQuery(v).text()) });
		return words;
	}
	// call this in a script right after the autocomplete box
	// e.g. get_autocomplete($('#tags','#mylist');
	// this should be added to the plugin shortcode as an option
	//TOD need listener to filter on return or on each new letter
	/**
	 * <div class="ui-widget">
  		<label for="tags">Tags: </label>
  		<input id="tags">
		</div>
		<script>jQuery(document).ready(function($){ get_autocomplete($('#mylist'),'#tags')})</script>
		<ul id="mylist"><li>this</li><li>list</li></ul>
		
	 * 
	 */
	function get_autocomplete(el,dest) {
		
		 jQuery(dest).autocomplete({
		      source: get_autocomplete_words(el)
		    });
		
	}

