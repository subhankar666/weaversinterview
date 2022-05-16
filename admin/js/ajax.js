
$=jQuery;
maximum=null;
$('.deleteBanner').each(function() {
    var value = parseInt($(this).attr('data-id'));
    maximum = (value > maximum) ? value : maximum;
  });
  

$(document).on("click","#pluginAddBanner",function(e){
    e.preventDefault();
    let actionType=$(this).attr('data-id') ? "update" : "add";
    let bannerId=$(this).attr('data-id') ?? "";
    let formData={
        action:"addBannerAction",
        bannerWidth:$("#bannerWidth").val(),
        bannerHeight:$("#bannerHeight").val(),
        bannerName:$("#bannerName").val(),
        bannerId:bannerId,
        actionType:actionType

    };
    console.log(formData);
    let modal=document.getElementById("myplugin-modal");
    ajaxFunction(siteUrl="",formData,true,modal,actionType);
    $(this).parent().find('input').val('');
});

$(document).on("click",".deleteBanner",function(){
    let formData={
        action:"removeBannerAction",
        id:$(this).attr("data-id")
    };
    ajaxFunction(siteUrl="",formData,true,"","remove");
    $(this).closest('tr').remove();
});

async function ajaxFunction(siteUrl,formData,reload,modal,action){
    $.post(
        ajaxurl,
        formData,
        function(response){
            let res=JSON.parse(response);
            if(res.success){
                toastr.info(res.message);
                if(modal){
                    modal.style.display = "none";
                }
                if(reload){
                    setTimeout(() => {
                        window.location.href=siteUrl;
                    }, 300);
                }
                if(action=="add"){
                    let className="";
                    maximum++;
                    if($("tr:last").hasClass("odd")){
                        className="even";
                        $(".dataTables_empty").parent().hide();
                    } else if($("tr:last").hasClass("even")){
                        className="odd";
                    }
                    $("#dataBody").append(`<tr class=${className}><td class="sorting_1" style="text-align:center">${formData.bannerName}</td><td style="text-align:center">${formData.bannerHeight}</td><td  style="text-align:center">${formData.bannerWidth}</td><td style="text-align:center"><a class="updateBanner" href="javascript:void(0)" data-id=${maximum}>Update</td><td style="text-align:center"><a class="deleteBanner" href="javascript:void(0)" data-id=${maximum}>Delete</a></td></tr>`);
                } else if(action=="update"){
                    setTimeout(() => {
                        window.location.href=siteUrl;
                    }, 300);
                }
                
            } else{
                toastr.error(res.message);
            }
        })
}