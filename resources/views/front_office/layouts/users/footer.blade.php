
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <!-- <script src="js/jquery.min.js"></script> -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ asset('assets/js/back_office/bootstrap.min.js') }}"></script>

    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="{{ asset('assets/js/back_office/nifty.min.js') }}"></script>
    
    @if (isset($data['js']))
        @foreach ($data['js'] as $file)
            <script src="{{ asset($file.'?code='.rand(0,9999)) }}"></script>
        @endforeach
    @endif
    <!--=================================================-->
</body>
</html>
