let $=jQuery;
let ajaxUrl=frontend_ajax_object.ajaxurl;

$("#pluginRegister").click(function(e){
    e.preventDefault();
    let siteUrl=`${$(this).attr('data-url')}user-notification`;
    let formData=$("#registerForm").serialize();
    action="registerAction";
	$("#loading-bar-spinner").show();
    loginRegisterAjax(siteUrl,formData,action)
});
$("#pluginLogin").click(function(e){
    e.preventDefault();
    let siteUrl=`${$(this).attr('data-url')}user-dashboard`;
    let formData=$("#loginForm").serialize();
    action="loginAction";
    loginRegisterAjax(siteUrl,formData,action)
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
        })
}
