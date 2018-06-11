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