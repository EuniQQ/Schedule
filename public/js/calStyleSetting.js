var mainImgInp = document.getElementById("mainImgInp");
var headerImgInp = document.getElementById("headerImgInp");
var footerImgInp = document.getElementById
    ("footerImgInp");
var mainImgPre = document.getElementById("mainImgPre");
var headerImgPre = document.getElementById
    ("headerImgPre");
var footerImgPre = document.getElementById
    ("footerImgPre");



/**
 * 上傳img同時預覽
 */
mainImgInp.onchange = evt => {
    const [file] = mainImgInp.files
    mainImgPre.src = URL.createObjectURL(file);
}

headerImgInp.onchange = evt => {
    const [file] = headerImgInp.files
    headerImgPre.src = URL.createObjectURL(file);
}

footerImgInp.onchange = evt => {
    const [file] = footerImgInp.files
    footerImgPre.src = URL.createObjectURL(file);
}