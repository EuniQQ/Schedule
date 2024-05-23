
var cashAddBtn = document.getElementById('cashAddBtn');
var cashEditBtn = document.getElementById('cashEditBtn');
var cashDelBtn = document.getElementById('cashDelBtn');
var cashDoneBtn = document.getElementById('cashDoneBtn');
var cardAddBtn = document.getElementById('cardAddBtn');
var cardEditBtn = document.getElementById('cardEditBtn');
var cardDelBtn = document.getElementById('cardDelBtn');
var cardDoneBtn = document.getElementById('cardDoneBtn');


$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr
                ('content'),
            'Authorization': 'Bearer ' + getApiToken()
        }
    });

    /**
     * 取得apiToken
     */
    function getApiToken() {
        let issetApiToken = document.cookie.indexOf
            ("api_token");
        let apiToken = issetApiToken > 0 ? getCookie
            ('api_token') : null;
        return apiToken
    }

    /**
     * 雙擊欄位建立input更新內容
    */
    const apiUrl = '/api/spending/';
    dblclickToEdit(apiUrl);
})

$(document)
    .on('click', '#cashEditBtn', () => {
        this.cashToogleBtns();
    })

    .on('click', '#cashDoneBtn', () => {
        this.cashToogleBtns();
    })

    .on('click', '#cardEditBtn', () => {
        this.cardToogleBtns();
    })

    .on('click', '#cardDoneBtn', () => {
        this.cardToogleBtns();
    })

    .on('click', '.modalClose', () => {
        clearModalInput();
    })

    .on('click', '#cashDelBtn', () => {
        const cashChbxs = document.querySelectorAll('.cashTbody input[type=checkbox]:checked');
        this.delChecked(cashChbxs);
    })

    .on('click', '#cardDelBtn', () => {
        const cardChbxs = document.querySelectorAll('.cardTbody input[type=checkbox]:checked');
        this.delChecked(cardChbxs);
    })


/**
 * 刪除已勾選的CASH/CARD選項
 * @param {*} checkboxes 已勾選之checkbox
 */
function delChecked(checkboxes) {
    const checkedArr = [];


    for (let i = 0; i < checkboxes.length; i++) {
        checkedArr.push(checkboxes[i].value);
    }

    $.ajax({
        url: '/api/spending',
        method: 'POST',
        data: {
            _method: 'DELETE',
            ids: checkedArr
        },
        success: (res) => {
            window.location.reload();
        },
        error: (err) => {
            alert(err);
        }
    })
}

function cashToogleBtns() {
    const cashChboxs = document.querySelectorAll('.cashChbox');
    cashChboxs.forEach(item => {
        item.classList.toggle('d-none');
    });
    cashAddBtn.classList.toggle('d-none');
    cashEditBtn.classList.toggle('d-none');
    cashDelBtn.classList.toggle('d-none');
    cashDoneBtn.classList.toggle('d-none');
}


function cardToogleBtns() {
    const cardChboxs = document.querySelectorAll('.cardChbox');
    cardChboxs.forEach(item => {
        item.classList.toggle('d-none');
    });
    cardAddBtn.classList.toggle('d-none');
    cardEditBtn.classList.toggle('d-none');
    cardDelBtn.classList.toggle('d-none');
    cardDoneBtn.classList.toggle('d-none');
}

/* 清空所有Modal input 內容 */
function clearModalInput() {
    const inputs = document.querySelectorAll('.modal input');
    const errors = document.querySelectorAll('.modal-body small');
    inputs.forEach(inp => {
        inp.value = '';
    });
    errors.forEach(err => {
        err.textContent = '';
    })
}

