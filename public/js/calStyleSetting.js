var headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
var mainImgInp = document.getElementById("mainImgInp");

var headerImgInp = document.getElementById("headerImgInp");

var footerImgInp = document.getElementById("footerImgInp");

var mainImgPre = document.getElementById("mainImgPre");

var headerImgPre = document.getElementById("headerImgPre");

var footerImgPre = document.getElementById("footerImgPre");

var offcanvasSmt = document.getElementById("offcanvasSmt");

var ftColorInp = document.getElementById("ftColorInp");

var calColorInp = document.getElementById("calColorInp");


/**
 * (通用)上傳img同時預覽、可刪除
 */
function previewSelect(event) {
    const inputId = event.target.id;
    const input = document.getElementById(inputId);
    const file = input.files[0];
    const imgSet = input.parentNode.nextElementSibling;
    const imgPre = imgSet.querySelector('img');

    if (file) {
        imgPre.src = URL.createObjectURL(file);
    } else {
        imgPre.src = "";
    }
}


$(document).on("click", "#offcanvasSmt", function (e) {
    const year = offcanvasSmt.getAttribute("data-year");
    const month = offcanvasSmt.getAttribute("data-month");
    const userId = offcanvasSmt.getAttribute("data-userId");
    const mainImg = mainImgInp.files[0];
    const headerImg = headerImgInp.files[0];
    const footerImg = footerImgInp.files[0];
    const ftColor = ftColorInp.value;
    const calColor = calColorInp.value;

    const formData = new FormData();
    formData.append("main_img", mainImg);
    formData.append("header_img", headerImg);
    formData.append("footer_img", footerImg);
    formData.append("footer_color", ftColor);
    formData.append("bg_color", calColor);
    formData.append("user_id", userId);

    $.ajax({
        url: "/api/calender/style/" + year + "/" + month,
        method: "POST",
        dataType: "json",
        headers: headers,
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
            console.log(res)
        },
        error: function (error) {
            console.log(error);
        }
    })
})