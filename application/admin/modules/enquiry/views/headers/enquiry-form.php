<SCRIPT>
    $(function() {
        $('.date-range-picker').daterangepicker({
            timePicker: true,
            format: 'DD-MM-YYYY',
            timePickerIncrement: 5,
            timePicker12Hour: false,
            timePickerSeconds: false
        });
    });
    function openAddEnquiry() {    
        $(".nav-tabs li").each(function (index) {
            $(this).removeClass('active');
            if(index == 1){
                $(this).addClass('active');
            }
        });
        $('#tab_1').removeClass('active');
        $('#tab_2').removeClass('active');
        $('#tab_2').addClass('active');
        
    }
</SCRIPT>
