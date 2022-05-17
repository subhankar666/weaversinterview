let $=jQuery;
$(document).ready(function() {
    $('#example').DataTable({
      "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ],
    });
} );
// Get the modal
var modal = document.getElementById("myplugin-modal");
// Get the button that opens the modal
var btn = document.getElementById("myPluginBtn");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("PluginClose")[0];
// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

$(document).on("click",".updateBanner",function(){
  console.log($(this).parent().parent().find("td:eq(0)").text());
  let dataId=$(this).attr('data-id');
  modal.style.display = "block";
  $("#pluginAddBanner").text("update");
  $("#pluginAddBanner").attr("data-id",dataId);
  $("#bannerName").val($(this).parent().parent().find("td:eq(0)").text());
  $("#bannerWidth").val($(this).parent().parent().find("td:eq(1)").text());
  $("#bannerHeight").val($(this).parent().parent().find("td:eq(2)").text());
});
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1500",
  "timeOut": "1500",
  "extendedTimeOut": "1500",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};