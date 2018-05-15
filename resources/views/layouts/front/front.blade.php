<!DOCTYPE html>
<html lang="en">
    @include('front.includes.head')
    <body>

        @include('front.includes.header')

        <!-- page content -->
        @yield('content')


        @include('front.includes.footer')
        @include('front.includes.script')

   
    </body>
</html>
