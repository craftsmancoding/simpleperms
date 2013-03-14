<style>
	/* In-line Styles for convenience (being a bit lazy here) */
	#modxcmp_mgr_page{
		margin-top:20px;
	}
	ul.modxcmp_list {}
	li.modxcmp_item {}
	div.modxcmp_error{
		border: 2px dotted red;
		padding: 10px;
		margin: 20px;
		background-color: #ff9999;
		width: 70%;
	}
	div.modxcmp_success{
		border: 2px dotted green;
		padding: 10px;
		margin: 20px;
		background-color: #99FF99;
		width: 70%;	
	}
	div.modxcmp_warning {
		border: 2px dotted #ffcc00;
		padding: 10px;
		margin: 20px;
		background-color: #FFFF99;
		width: 70%;
	}

	#modxcmp_footer {
		margin-top:20px;
	}
	.modxcmp_label {
		font-weight: bold;
		display: block;
	}
	.modxcmp_input {
		display: block;
		padding: 4px;
		margin: 5px;
		width: 200px;
	}
	.modxcmp_desc {
		font-style: italic;
	}
	table.modxcmp_table td {
		padding: 10px;
	}
	tr.modxcmp_row {
		height: 30px;
	}
	td.modxcmp_name_cell {
		width:400px;
	}
	td.modxcmp_view_cell {
		width:80px;
	}
	tr.modxcmp_even {
		background-color: #F8F8F8;
	}
	tr.modxcmp_odd {
		background-color: #C8C8C8;	
	}
	
	a.modxcmp_button {
	    appearance: button;
	    -moz-appearance: button;
	    -webkit-appearance: button;
	    text-decoration: none; font: menu; color: ButtonText;
	    display: inline-block; padding: 2px 8px;	
	}
</style>

<div id="modxcmp_mgr_page">
	<h2>Simple Permissions</h2>
	
	<h3><?php print $pagetitle; ?></h3>
	
	<?php print $msg; ?>
	
	<?php print $content; ?>
	
	<div id="modxcmp_footer">
		&copy; 2013 <a href="http://craftsmancoding.com/">Craftsman Coding</a>
	</div>
</div>