	<script src="{{asset('assets/front/js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/front/js/owl.carousel.min.js')}}"></script>
	<script src="{{asset('assets/front/js/css3-animate-it.js')}}"></script>
	<script src="{{asset('assets/front/js/custom.js')}}"></script>
	<!-- Scripts -->
	
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
	<script src="{{asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

	<script src="{{asset('assets/admin/custom.min.js')}}"></script>
