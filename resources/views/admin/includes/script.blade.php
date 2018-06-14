	<script src="{{asset('assets/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
	
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

	<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
	<script src="{{asset('assets/admin/nicEdit.js')}}"></script>
	<script src="{{asset('assets/admin/custom.min.js')}}"></script>

	<script type="text/javascript">
		new nicEditor({fullPanel : true,iconsPath : '{{asset("public/assets/admin/nicEditorIcons.gif")}}'}).panelInstance('editor');
		new nicEditor({fullPanel : true,iconsPath : '{{asset("public/assets/admin/nicEditorIcons.gif")}}'}).panelInstance('editor-it');
	</script>
	
	<!-- <script>
    ClassicEditor
    .create( document.querySelector( '#editor' ),{
        
    } )
    .then( editor => {
        console.log( Array.from( editor.ui.componentFactory.names ) );
    } )
    .catch( error => {
        console.error( error );
    } );
	</script> -->



 