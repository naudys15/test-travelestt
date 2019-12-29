
    </div>
    <!--===================================================-->
    <!--End page content-->

    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->

        <!--MAIN NAVIGATION-->
        <!--===================================================-->
        <nav id="mainnav-container">
            <div id="mainnav">

                <!--OPTIONAL : ADD YOUR LOGO TO THE NAVIGATION-->
                <!--It will only appear on small screen devices.-->
                <!--================================
                <div class="mainnav-brand">
                    <a href="index.html" class="brand">
                        <img src="img/logo.png" alt="Nifty Logo" class="brand-icon">
                        <span class="brand-text">Nifty</span>
                    </a>
                    <a href="#" class="mainnav-toggle"><i class="pci-cross pci-circle icon-lg"></i></a>
                </div>
                -->
                @include('back_office/layouts/sidebar')
            </div>
        </nav>
        <!--===================================================-->
        <!--END MAIN NAVIGATION-->

        </div>

        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>

            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">
                14GB of <strong>512GB</strong> Free.
            </div>

            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <p class="pad-lft">&#0169; 2018 Your Company</p>

        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->

        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
        </div>
        <!--===================================================-->
        <!-- END OF CONTAINER -->

        <!--JAVASCRIPT-->
        <!--=================================================-->

        <!--jQuery [ REQUIRED ]-->
        <script src="{{ asset('assets/js/jquery.js') }}"></script>

        <!--BootstrapJS [ RECOMMENDED ]-->
        <script src="{{ asset('assets/js/back_office/bootstrap.min.js') }}"></script>

        <!--NiftyJS [ RECOMMENDED ]-->
        <script src="{{ asset('assets/js/back_office/nifty.min.js') }}"></script>

        @if (isset($data['leaflet']))
            <script src="{{ asset('assets/js/leaflet.js') }}"></script>
            <script src="{{ asset('assets/js/leaflet-routing-machine.min.js') }}"></script>
        @endif
        @if (isset($data['js']))
            @foreach ($data['js'] as $file)
                <script src="{{ asset($file.'?code='.rand(0,9999)) }}"></script>
            @endforeach
        @endif

        @yield('script')
        <!--=================================================-->
        <!--Demo script [ DEMONSTRATION ]-->
        <!-- <script src="js/demo/nifty-demo.min.js"></script> -->
        <!--Flot Chart [ OPTIONAL ]-->
        <!-- <script src="plugins/flot-charts/jquery.flot.min.js"></script>
        <script src="plugins/flot-charts/jquery.flot.categories.min.js"></script>
        <script src="plugins/flot-charts/jquery.flot.orderBars.min.js"></script>
        <script src="plugins/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="plugins/flot-charts/jquery.flot.resize.min.js"></script> -->
        <!--Specify page [ SAMPLE ]-->
        <!-- <script src="js/demo/dashboard-2.js"></script> -->
    </body>
</html>
