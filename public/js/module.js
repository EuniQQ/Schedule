
/**
 * 取得apiToken
 */
function getApiToken() {
    let issetApiToken = document.cookie.indexOf("api_token");
    let apiToken = issetApiToken > 0 ? getCookie('api_token') : null;

    return apiToken
}


/**
 * 取得特定cookie值
 */
function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length == 2) {
        return parts.pop().split(";").shift();
    }
}



/**
 * 上傳img同時預覽、可刪除
 */
function previewSelect(e) {
    const inputId = e.target.id;
    const input = document.getElementById(inputId);
    const file = input.files[0];
    const imgSet = input.nextElementSibling;
    const imgPre = imgSet.querySelector('img');

    if (file) {
        imgPre.src = URL.createObjectURL(file);
    } else {
        imgPre.src = "";
    }
}
