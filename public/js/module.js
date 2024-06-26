var errGroup = null;

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



/**
 * 
 * @param {*} res get journal/event res
 */
function makeYearListOpt(res) {
    Object.values(res.yearList).forEach(year => {
        let opt = document.createElement('option');
        opt.textContent = year;
        yearSel.appendChild(opt);
    })
}


/**
 * 顯示驗證失敗錯誤訊息
 * @param err =error message
 */
function showErrMsgFromModal(err) {
    let errMessage = err.responseJSON.message;
    let messages = errMessage.split('\r');  // 將err照換行符分成多行
    const modalBody = document.getElementById('modalBody');
    errGroup = document.createElement('div');
    errGroup.id = 'errGroup';
    messages.forEach(message => {
        let showErr = document.createElement('div');
        showErr.textContent = message;
        showErr.className = "alert alert-danger";
        errGroup.appendChild(showErr);
    })
    modalBody.insertBefore(errGroup, modalBody.firstChild);
}


/**
 * 清除驗證錯誤訊息
 */
function removeErrMsg() {
    if (errGroup !== null) {
        errGroup.remove();
        errGroup = null;
    }
}



function getDay() {
    const day = new Date();
    const year = day.getFullYear();
    // 月份從0開始，需加1。加前導0
    const month = String(day.getMonth() + 1).padStart(2, '0');
    const date = String(day.getDate()).padStart(2, '0');
    const today = `${year}-${month}-${date}`;

    let data = {
        thisYear: year,
        thisMonth: month,
        thisDate: date,
        today: today
    }
    return data;
}