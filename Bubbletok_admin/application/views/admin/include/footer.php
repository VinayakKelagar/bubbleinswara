
<!-- Footer -->
<footer class="sticky-footer">
    <div class="container">
        <div class="text-center">
            <small class="font-15">Copyright © Elaunch Solution Made With <i class="fa fa-heart cl-danger"></i> In India</small>
        </div>
    </div>
</footer>
<!-- /Footer -->

<!-- Switcher Start -->
<div class="w3-sidebar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;" id="rightMenu">
    <div class="rightMenu-scroll">

        <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large theme-bg">Setting Pannel <i class="ti-close"></i></button>
        <div class="right-sidebar" id="side-scroll">
            <div class="user-box">

                <div class="profile-img">

                    <img src="http://localhost/joywallet/assets/dist/img/logo.png" alt="user">


                    <!-- this is blinking heartbit-->
                    <div class="notify setp"> <span class="heartbit"></span> <span class="point"></span> </div>
                </div>
                <div class="profile-text">
                    <h4>name</h4>
                    <a href="" class="bg-info-light"><i class="ti-settings"></i></a>
                    <a href="" class="bg-danger-light"><i class="ti-power-off"></i></a>
                </div>

                <div class="tabbable-line">

                    <ul class="nav nav-tabs ">

                        </ul>

                        <div class="tab-content">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- /Switcher -->
<script type="text/javascript">
    var base_url = '<?= base_url(); ?>';

</script>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded cl-white theme-bg" href="#page-top">
    <i class="ti-angle-double-up"></i>
</a>

<!-- Bootstrap core JavaScript-->

<script src="<?= base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url(); ?>assets/plugins/jquery-easing/jquery.easing.min.js"></script>

<!-- Slick Slider Js -->
<script src="<?= base_url(); ?>assets/plugins/slick-slider/slick.js"></script>

<!-- Slim Scroll -->
<script src="<?= base_url(); ?>assets/plugins/slim-scroll/jquery.slimscroll.min.js"></script>


<!-- InputMask -->
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?= base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- Angular Tooltip -->


<!-- Morris.js charts -->
<script src="<?= base_url(); ?>assets/plugins/morris.js/morris.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/raphael/raphael.min.js"></script>

<script src="<?= base_url(); ?>assets/plugins/angular-tooltip/angular.js"></script>
<script src="<?= base_url(); ?>assets/plugins/angular-tooltip/angular-tooltips.js"></script>
<script src="<?= base_url(); ?>assets/plugins/angular-tooltip/index.js"></script>

<!--<script src="--><?//= base_url(); ?><!--assets/dist/js/custom/iCheck.js" ></script>-->
<script src="<?= base_url(); ?>assets/dist/js/custom/form-element.js"></script>


<script src="<?= base_url(); ?>assets/dist/js/jquery.validate.js"></script>
<script src="<?= base_url(); ?>assets/dist/js/jquery.validate.min.js"></script>


<script src="<?= base_url(); ?>assets/dist/js/add-pharmacy.js"></script>

<!-- Custom Chart JavaScript -->
<script src="<?= base_url(); ?>assets/dist/js/custom/dashboard/dashboard-3.js"></script>


<!-- Data Table Js -->
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script src="<?= base_url(); ?>assets/plugins/sweetalert/js/sweetalert.js"></script>

<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>

<!-- bootstrap datepicker -->
<script src="<?= base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- Select2 -->
<script src="<?= base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>

<!-- bootstrap color picker -->
<script src="<?= base_url(); ?>assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<!-- bootstrap time picker -->
<script src="<?= base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>


<!-- Custom scripts for all pages -->
<script src="<?= base_url(); ?>assets/dist/js/glovia.js"></script>
<script src="<?= base_url(); ?>assets/dist/js/jQuery.style.switcher.js"></script>
<script src="<?= base_url(); ?>assets/dist/js/jquery.toast.js"></script>
<script src="<?= base_url(); ?>assets/dist/js/custom.js"></script>
<!-- <script src="<?= base_url(); ?>assets/dist/js/custom/vendor-chat.js"></script> -->

<script>

    $(".loading").hide();

</script>


<script>
    function openRightMenu() {
        document.getElementById("rightMenu").style.display = "block";
    }
    function closeRightMenu() {
        document.getElementById("rightMenu").style.display = "none";
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#styleOptions').styleSwitcher();
    });
</script>

<script>
    $('.dropdown-toggle').dropdown()
</script>

<script>
    $(function () {
        $(".heading-compose").click(function () {
            $(".side-two").css({
                "left": "0"
            });
        });

        $(".newMessage-back").click(function () {
            $(".side-two").css({
                "left": "-100%"
            });
        });
    })
</script>
</div>
<!-- Wrapper -->
<script>

    $(function () {
        "use strict";
        var a, i = ["red-skin", "blue-skin", "green-skin", "yellow-skin", "purple-skin", "cyan-skin", "red-skin-light", "blue-skin-light", "green-skin-light", "yellow-skin-light", "purple-skin-light", "cyan-skin-light"];

        function o(e) {
            var a, o;
            return $.each(i, function (e) {
                $("body").removeClass(i[e])
            }), $("body").addClass(e), a = "skin", o = e, "undefined" != typeof Storage ? localStorage.setItem(a, o) : window.alert("Please use a modern browser to properly view this template!"), !1
        }
        (a = void("undefined" != typeof Storage || window.alert("Please use a modern browser to properly view this template!"))) && $.inArray(a, i) && o(a), $("[data-skin]").on("click", function (e) {
            $(this).hasClass("knob") || (e.preventDefault(), o($(this).data("skin")))
        })
    });
</script>
<script type="text/javascript">
   
    var dataTable = $('#user-table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/User/showUsers',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

    var dataTable1 = $('#transaction-history').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/Transaction/showTransaction',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

    var dataTable2 = $('#core-wallet-table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/Core_Wallet/showWallettransaction',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

    var dataTable1 = $('#conatct-us-table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/User/showContact',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

    var dataTable1 = $('#notification-table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "order": [[ 0, "desc" ]],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/User/showNotification',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

    var dataTable1 = $('#level-table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'<?php echo base_url(); ?>admin/Refferal/showlevel',
       'data': function(data){
          // Read values
          // var country = $('#country').val();

          // Append to data
          // data.country = country;
       }
    }
    });

// function callfun()
// {
//     dataTable.draw();
// }
</script>
</body>
</html>