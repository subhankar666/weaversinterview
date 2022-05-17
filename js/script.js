$=jQuery;
let imgRes;
let _URL = window.URL || window.webkitURL;
let base64data;
let formData;
let fileName;
let currentUser=$("#pluginUpload").attr('data-user');
// Register Page

const container = document.getElementById('container-register');

$("#signUp").click(function(){
    console.log("test");
	container.classList.add("right-panel-active");
});
$("#signIn").click(function(){
    console.log("test");
	container.classList.remove("right-panel-active");
});

// Register Page
$("#bannerSize").change(function(e){
    bannerSize=$('#bannerSize option:selected').val();
});
$("#pluginUpload").click(function(){
    siteUrl='';
    fileName=$("#bannerUpload").val().replace(/.*(\/|\\)/, '')
    formData={
        base64data:base64data,
        filename:fileName,
        action:"uploadFile",
        userID:currentUser
    }
    $("#bannerUpload").val('');
    let bannerSize=$('#bannerSize option:selected').val();
    if(bannerSize != imgRes){
        toastr.warning("Please Upload proper Size");
    } else{
        ajaxFunction(siteUrl,formData,false);
        $("#showBanner").append('<div class="gallery-item"><img src="'+base64data+'" class="gallery-image"></div>');
    }
});

$("#bannerUpload").change(function(e){
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        var objectUrl = _URL.createObjectURL(file);
        img.onload = function () {
                imgRes=`${this.width}x${this.height}`;
                _URL.revokeObjectURL(objectUrl);
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function(){
                base64data = reader.result;
                
            }
        };
        img.src = objectUrl;
    }
});
