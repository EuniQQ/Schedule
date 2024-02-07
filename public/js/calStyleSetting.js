var headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}

// OFFCANVAS 表單
var mainImgInp = document.getElementById("mainImgInp");

var headerImgInp = document.getElementById("headerImgInp");

var footerImgInp = document.getElementById("footerImgInp");

var ftColorInp = document.getElementById("ftColorInp");

var calColorInp = document.getElementById("calColorInp");

var mainImgPre = document.getElementById("mainImgPre");

var headerImgPre = document.getElementById("headerImgPre");

var footerImgPre = document.getElementById("footerImgPre");

var styleCanvas = document.getElementById("offcanvasRight");

var offcanvasSmt = document.getElementById("offcanvasSmt");

var offcanvasId = offcanvasSmt.getAttribute("data-id");

// 月曆頁
var mainImg = document.getElementById("mainImg");

var headerImg = document.getElementById("headerImg");

var footerImg = document.getElementById("footerImg");

var footer = document.querySelector(".footer");

var oddCalElements = document.querySelectorAll(".singleDay:nth-child(odd)");

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


/**
 * 監聽offcanvas表單元素的變更事件
 */
var offcvsChgs = new FormData();
styleCanvas.addEventListener('change', function (e) {
    const target = e.target;
    const id = offcanvasSmt.getAttribute('data-id');
    const inpName = target.name;
    if (target.tagName === 'INPUT') {
        if (target.type === 'file') {
            const file = target.files[0];
            offcvsChgs.append(inpName, file);
        } else {
            offcvsChgs.append(inpName, target.value);
        }
    }
})


$(document).on("click", "#offcanvasSmt", function (e) {

    if (!offcanvasId) {
        sendCreatAjax();
    } else {
        sendUpdateAjax();
    }
})

function sendCreatAjax() {
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
            updateAfterAjax(res);
        },
        error: function (error) {
            console.log(error);
        }
    })
}


function sendUpdateAjax() {
    $.ajax({
        url: "/api/calender/style/" + offcanvasId,
        type: "POST",
        dataType: "json",
        headers: headers,
        data: offcvsChgs,
        contentType: false,
        processData: false,
        success: function (res) {
            updateAfterAjax(res);
        },
        error: function (error) {
            console.log(error);
        }
    })
    offcvsChgs = new FormData();  // reset
    styleCanvas.style.display = "none";
}


/**
 * update style of calender after setting style
 * @param {*} res = json
 */
function updateAfterAjax(res) {
    mainImg.src = mainImgPre.src = res.mainImg;
    headerImg.src = headerImgPre.src = res.headerImg;
    footerImg.src = footerImgPre.src = res.footerImg;
    footer.style.backgroundColor = res.footer_color;
    ftColorInp.value = res.footer_color;
    oddCalElements.forEach(function (element) {
        element.style.backgroundColor = res.bg_color;
    });
    calColorInp.value = res.bg_color;
}