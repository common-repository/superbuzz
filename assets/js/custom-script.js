jQuery(document).ready(function($) {  
     // API Key Validation 
    $(document).on("click", ".superbuzz_apikey_submit", function(e) {
        e.preventDefault();
        var apikeyValue = $('form').find('input[name="superbuzz_apikey"]').val();
        if(apikeyValue.trim() != '' ) {  
            $('.appIDMissing').hide();   
            $.ajax({
                type: "POST",
                dataType: "json",
                url: my_nonce_data.nonceUrl,
                data: { action: "appid_superbuzz_submit", apiId: apikeyValue},
                success: function (response) {      
                    if(response.success == true) {
                        crul_request_app_id_validated(apikeyValue);   
                    }                        
                }
            }); 
        }else {           
            $('.appIDMissing').show("");             
            $('.appIDMissing').html("<p>APP ID is missing</p>");             
        }       
                        
    }); 

    // api call for validate
    function crul_request_app_id_validated(apikeyValue){
        $.ajax({
            type: "GET",
            dataType: "json",
            url: ajaxurl,
            data: { action: "crul_app_id_validated" },
            success: function (response) {  
                if(response.success) {
                    if(response.app_id == apikeyValue){
                        $(".superbuzz_apikey_submit").val("Validated");                        
                        $('.superbuzz_apikey').prop('disabled', true);
                        $('.superbuzz_apikey_submit').prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: ajaxurl,
                            data: { action: "table_superbuzz_apiresponse", apiResponse: response.success },
                            success: function (responseapp) {                                  
                                if(responseapp.success) {
                                    $('.appIDValidated').show("");
                                    $('.appIDValidated').html("<p> APP ID Validated</p>");
                                }                   
                            }
                        });
                    } else {
                        $('.appIDMissing').show();  
                        $('.appIDMissing').html("<p> Your APP id is not validated </p>");
                    } 
                }else {  
                    $('.appIDMissing').show();  
                    $('.appIDMissing').html("<p> The domain " + '"' + response.domain +  '"' +  " is not exist in SuperBuzz please make sure you add the URL in your SuperBuzz dashboard then try again.</p>");  
                }              
            }
        });  
    }    
});