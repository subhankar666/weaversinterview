let $=jQuery;
let ajaxUrl=frontend_ajax_object.ajaxurl;

$("#pluginRegister").click(function(e){
    e.preventDefault();
    let siteUrl=`${$(this).attr('data-url')}user-notification`;
    let formData=$("#registerForm").serialize();
    action="registerAction";
	$("#loading-bar-spinner").show();
    loginRegisterAjax(siteUrl,formData,action)
    $("#loading-bar-spinner").hide();
});
$("#pluginLogin").click(function(e){
    e.preventDefault();
    let siteUrl=`${$(this).attr('data-url')}user-dashboard`;
    let formData=$("#loginForm").serialize();
    action="loginAction";
    loginRegisterAjax(siteUrl,formData,action)
});
$(document).on("click",".dashboard-button",function(){
    if($(this).text() == "APPLY"){
        $(this).text("APPLIED");
    }
    
    let courseId=$(this).attr('data-id');
    let formData=`couseId=${courseId}&action=applyCourse`;
    ajaxFunction("",formData,false)
   
});
function loginRegisterAjax(siteUrl,formData,action){
    formData=`${formData}&action=${action}`;
    ajaxFunction(siteUrl,formData,true)
    
}

function ajaxFunction(siteUrl,formData,reload){
    $.post(
        ajaxUrl,
        formData,
        function(response){
            console.log(response);
            
            let res=JSON.parse(response);
            if(res.success){
				$("#loading-bar-spinner").hide();
                toastr.info(res.message);
                if(reload){
                    setTimeout(() => {
                        window.location.href=siteUrl;
                    }, 300);
                }
                
            } else{
                toastr.error(res.message);
     
            }
            $("#loading-bar-spinner").hide();
        })
}
