<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
     <title>Traffic watch</title>
	 <!-- <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" /> -->
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
	 <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
</head>
	<body>
		<h1>Traffic Watch</h1>
		<div>
			<form id="trafficForm" method="post">
				<input id="trafficRange" name="range" type="text" value="3-5" />		
				<select id="traffic"> 
					<option value="1">Traffic</option> 
					<option value="0">No traffic</option> 
				</select>
				<input type="submit" value="Submit">
			</form>
		</div>
		</br>
		<h3>traffic state:</h3>
		<div id=roadState>
			<?php 
				echo "||";
				foreach ($road as $segment) {
					if ($segment[1] == 0)
						echo " - ";
					else
						echo " = ";
				}
				echo "||";
			?>
		</div>
   </body>
</html>
<script>
$(document).ready(function(){	
	
	$.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    	}, 
    	'Please use the valid pattern'
    );
    
	$("form").validate({
		rules: {
    		range: {
      			required: true,
      			regexp: /^([0-9]||[0-1][0-9])\-([0-9]||[0-1][0-9])((,[0-9]||[0-1][0-9])\-([0-9]||[0-1][0-9])?)*$/
      		}
    	},
 	});
 
	$("form").submit(function(event) {
		event.preventDefault();
		var $form = $(this);

         // check if the input is valid
    	if(! $form.valid()) return false;
		var urlAjax = "http://localhost/Apple/traffic/" + $("input#trafficRange").val();
		
		// change type of request if no traffic
		var typeRequest = "POST";
		if ($("select#traffic").val() == 0) typeRequest = "DELETE";
		
		$.ajax({
			type: typeRequest,
			url: urlAjax,
			contentType: "application/json",
 			dataType: "json",
			success: function(data) { 
				var display = '||';
				$.each(data, function(i,item) {
					if (item[1] == 0)
						display += " - ";
					else
						display += " = ";
 				});
 				display += '||'
				$('#roadState').html(display);
			},
			error: function(data) {alert("ajax error"); },
		});
		return 0;
	});
 });
</script>