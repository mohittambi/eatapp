	<script src="{{asset('assets/front/js/jquery-2.2.4.min.js')}}"></script>
	<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
	<!-- <script src="{{asset('assets/front/js/owl.carousel.min.js')}}"></script> -->
	<script src="{{asset('assets/front/js/css3-animate-it.js')}}"></script>
	<!-- Scripts -->
	<script type="text/javascript">
		$(document).ready(function(){
			$(window).click(function() {
			 	$("#navbar_d").hide();
			});
		   	$('.ac').click(function(event){
		    	event.stopPropagation();
		    	$("#navbar_d").toggle(1000);
			});
		});
	</script>
 	@yield('uniquepagescript')
	<script>
		$("input").on("keydown", function (e) {
			if (e.which === 32 &&  e.target.selectionStart === 0) {
				return false;
			}
		});
    </script>
    <script src="{{asset('assets/admin/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
       $(".validate").validate();
    </script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {

	    <?php
	    if(isset($all_na) && !empty($all_na)){$all_na=$all_na;}else{$all_na='';}?>
		var all_na = '<?php echo $all_na; ?>' ;
		var events_array = JSON.parse(all_na);
		
	    $('#calendar').fullCalendar({
	    	header: {
                left: 'title',
                center: '',
                right: ''
            },
            defaultView: 'month',
            editable: false,
            selectable: true,
            select: function(start, end) {
			    if(start.isBefore(moment())) {
			        $('#calendar').fullCalendar('unselect');
			        return false;
			    }
			},
			eventClick: function(calEvent, jsEvent, view) {

				var date =	new Date(calEvent.start);
		      	date = date.toISOString().slice(0,10);

		        $.ajax({
			    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			        url: '{{url('/calendar-days')}}',
			        data: {
			        	'date':date,
			        	'type':'remove'
			    		},
			        type:'POST',
			        cache:false,
			        // contentType: false,
			        // processData: false,
			        success:function(response){
			        	// console.log(response);
			        	location.reload();
			        },
			    });

		    },
			dayClick: function(date, jsEvent, view, resourceObj) {
			if (date.month() ==  view.intervalStart.month()) {
			    $.ajax({
			    	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			        url: '{{url('/calendar-days')}}',
			        data: {
			        		'date':date.format(),
			        		'type':'add'
			    		},
			        type:'POST',
			        cache:false,
			        // contentType: false,
			        // processData: false,
			        success:function(response){
			        	// console.log(response);
			        	location.reload();
			        },
			    });
			}		    
			},
			
	    	events: events_array, // this is the list of events in json format from database
	    	eventColor: 'red',
	    	eventBorderColor: 'red',
	        
	    });
	});
</script>
<script type="text/javascript">
    $(function () {
        $(".AHR").on("click", function () {
        	var errorsReport = 0;
        	for(var i = 0; i<7; i++){
    			var opening , closing;
    		    var strOpen = $(".opening"+i).val();
				opening = strOpen.replace(/:/, "");

    		    var strClose = $(".closing"+i).val();
				closing = strClose.replace(/:/, "");
				if(opening>closing){
					$(".errors"+i).html("Closing time should be greater than opening time.");
					errorsReport++;
				}
	        	//console.log("opening = " + $(".opening"+i).val() +", closing = "+ $(".closing"+i).val());
	        	//console.log("opening = " + opening +", closing = "+ closing);
        	}
        	if(errorsReport==0){
        		$("#settings-form").submit();
        	}
        	setTimeout(function(){ $(".errorsContainer").html("");  }, 5000);
            //alert("testin");
        });

    });
    
    //setTimeout(function(){ alert("Hello"); }, 3000);
</script>
<script src="{{asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery-clockpicker.min.js')}}"></script>
<script src="{{asset('assets/admin/custom.min.js')}}"></script>
<script>
$('.clockpicker').clockpicker({
	autoclose: true,
    'default': 'now'
});

$(document).ready(function(){
    activaTab('home');
});

function activaTab(tab){
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
</script>


