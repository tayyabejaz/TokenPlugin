function jsfunc(token)
{
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        jQuery('#submit_btn').click(function() {
            var data_str = 'token=' + token;
            jQuery.ajax({
                type: "POST",
                url: "wp-content/plugins/NewPlugin/assets/transaction.php",
                data: data_str,
                cache: false,
                success:
                    function(result)
                    {
                        jQuery('#disp').html(result);
                        alert("Transfer Successful");
                    }
            });
            return false;
        });
    });
}