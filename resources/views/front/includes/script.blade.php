	<script src="{{asset('assets/front/js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/front/js/owl.carousel.min.js')}}"></script>
	<script src="{{asset('assets/front/js/css3-animate-it.js')}}"></script>
	<script src="{{asset('assets/front/js/custom.js')}}"></script>
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

	    var date = new Date();
	    var d = date.getDate();
	    var m = date.getMonth();
	    var y = date.getFullYear();
	    
		var all_na = '<?php echo $all_na; ?>' ;
		//console.log(all_na);
		//alert(all_na);
	
	 //    var events_array = [
	 //        {
		//         title: 'Test1',
		//         start: '2018-06-01',
		//         tip: 'Personal tip 1'
		//     },
		//     {
		//         title: 'Test2',
		//         start: '2018-06-02',
		//         tip: 'Personal tip 2'
		//     }
		// ];
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
			        	// alert(response);
			        	location.reload();
			        
			        },
			    });


		      if (calEvent.url) {
		        alert(
		          'Clicked ' + calEvent.date + '.\n' +
		          'Will open ' + calEvent.url + ' in a new tab'
		        );

		        window.open(calEvent.url);

		        return false; // prevents browser from following link in current tab.
		      } else {
		      	var date =	new Date(calEvent.start);
		      	date = date.toISOString().slice(0,10);
		        //alert('Clicked ' + date);
		      }
		    },
			dayClick: function(date, jsEvent, view, resourceObj) {
			    //alert('Date: ' + date.format());
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
			        	location.reload();
			        	// console.log(response);
			        	// alert(response);
			        
			        },
			    });
			    
			},
	    	events: events_array,
	        
	        
	    });
	});

	    
</script>
<script>
	
</script>
	<script src="{{asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

	<script src="{{asset('assets/admin/custom.min.js')}}"></script>
