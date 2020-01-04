(function($) {
	
	var dateFormat = "yy-mm-dd",
	from = $( "#from" )
	.datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		minDate: new Date('2019-10-02')
	})
	.on( "change", function() {
		to.datepicker( "option", "minDate", getDate( this ) );
	}),
	to = $( "#to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1
	})
	.on( "change", function() {
		from.datepicker( "option", "maxDate", getDate( this ) );
	});

	function getDate( element ) {
		var date;
		try {
			date = $.datepicker.parseDate( dateFormat, element.value );
		} catch( error ) {
			date = null;
		}

		return date;
	}


	$(document).on("click", '#load', function(){

		var from = $("#from").val();
		var to = $("#to").val();
		var group = $('input[name="group"]:checked').val();

		var error = false;


		if( from.length === 0 ){
			$("#from").addClass('is-invalid');
			error = true;
		} else {
			$("#from").removeClass('is-invalid');
		}

		if( to.length === 0 ){
			$("#to").addClass('is-invalid');
			error = true;
		} else {
			$("#to").removeClass('is-invalid');
		}

		if(error == true){
			return false;
		} else {

			$(".fa.fa-spinner").addClass("loading");
			$.post("/getProdutos.php", {'start': from, 'end': to, 'group': group }, function(data){

				if (data.error == true){
					window.alert(data.message);
				} else {
					$("#table").html(data.body);
				}
				$(".fa.fa-spinner").removeClass("loading");

			}, 'json');

		}

	});



})(jQuery);