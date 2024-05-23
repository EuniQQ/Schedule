
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
    dblclickToEdit();
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


function dblclickToEdit() {
    document.querySelectorAll('td').forEach(td => {
        td.addEventListener('dblclick', (e) => {

            // 取得當前td內容
            const originalContent = td.textContent;
            const id = e.target.getAttribute('data-id');
            const colName = e.target.getAttribute('data-name');

            // 創建input
            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalContent;

            // 將input加到td中
            td.textContent = '';
            td.appendChild(input);

            // 自動聚焦並選中內容
            input.focus();
            input.select();

            input.addEventListener('keydown', (e) => {

                if (e.key === 'Enter' && input.value !== originalContent) {
                    $.ajax({
                        url: '/api/spending/' + id,
                        type: 'POST',
                        data: {
                            _method: 'PATCH',
                            name: colName,
                            value: input.value.trim()
                        },
                        success: function (res) {
                            console.log(res);
                            td.textContent = input.value.trim();
                            if (res.total !== undefined) {
                                const total = document.getElementById(res.totalId);
                                total.textContent = 'Total：$' + res.total;
                            }
                        },
                        error: function (err) {
                            console.log(err);
                            td.textContent = originalContent;
                        }
                    });
                }
            });

            // iinput 失去焦點時，保存內容並恢復td
            input.addEventListener('blur', () => {
                td.textContent = input.value.trim() || originalContent;
            });
        })
    })
}
