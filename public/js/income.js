var addBtn = document.getElementById('addBtn');

var npoBtn = document.getElementById('npoBtn');

var npoTbody = document.getElementById('npoTbody');

var toggleSwitch = document.getElementById('flexSwitchCheckDefault');

var xIcons = document.getElementsByClassName('xIcon');


$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + getApiToken()
        }
    });

    /**
     * 取得apiToken
     */
    function getApiToken() {
        let issetApiToken = document.cookie.indexOf("api_token");
        let apiToken = issetApiToken > 0 ? getCookie('api_token') : null;
        return apiToken
    }

    /**
     * 雙擊欄位建立input更新內容
    */
    const apiUrl = '/api/income/';
    dblclickToEdit(apiUrl);

})

$(document)
    .on("click", "#npoBtn", function () {
        $.ajax({
            url: "/api/income/npoList",
            method: "GET",
            success: function (res) {
                console.log(res);
                makeNpoList(res);
            },
            error: function (err) {
                console.log(err);
            }
        })
    })

    .on("click", ".closeNpoModal", function () {
        clearNpoModal();
    })


    /**
     * 點選icon edit pen 內容顯示於modal下半部
     */
    .on("click", ".fa-pen", function (e) {
        let editId = e.target.getAttribute('data-id');
        $.ajax({
            url: "/api/income/npo/" + editId,
            method: "GET",
            success: function (res) {
                $("#method").val(res.method);
                $("#name").val(res.name);
                $("#account").val(res.account);
                $("#code").val(res.code);
                $("#bank").val(res.bank);
                $("#pay_on_line").val(res.pay_on_line);
                $("#form_link").val(res.form_link);
                let action = "/api/income/npo/" + editId;
                $("#editNpoForm").attr("action", action);
            }, error: function (err) {
                console.log(err);
            }
        })
    })


    /**
     * click trush icon to DELETE NPO DATA
     */
    .on("click", ".fa-trash", function (e) {
        let trushId = e.target.getAttribute('data-id');
        $.ajax({
            url: "/api/income/npo/" + trushId,
            method: "POST",
            data: {
                _method: "DELETE",
            },
            success: function (res) {
                clearNpoModal();
                makeNpoList(res);
            },
            error: function (err) {
                console.log(err)
            }
        })
    })


    .on("change", "#yearSelect", function (e) {
        const selectedYear = e.target.value;
        window.location.href = '/income/' + selectedYear;

    })


    /**
     * 監聽edit mode是否開啟
     */
    .on("change", "#flexSwitchCheckDefault", function (e) {
        if (e.target.checked) {
            showXIcons();
        } else {
            hideXIcons();
        }
    })

    .on("mouseover", ".xIcon", function (e) {
        e.target.classList.add('fa-2xl');
    })

    .on("mouseleave", ".xIcon", function (e) {
        e.target.classList.remove('fa-2xl');
    })

    .on("click", ".xIcon", function (e) {
        if (window.confirm('是否確定刪除此紀錄？')) {
            const id = e.target.getAttribute('data-id');
            $.ajax({
                url: "/api/income/" + id,
                type: "POST",
                data: {
                    "_method": "DELETE"
                },
                success: function (res) {
                    window.location.reload();
                },
                error: function (err) {
                    alert(err);
                }
            })
        }
    })

function showXIcons() {
    for (let i = 0; i < xIcons.length; i++) {
        xIcons[i].style = "display:flex";
    }
}

function hideXIcons() {
    for (let i = 0; i < xIcons.length; i++) {
        xIcons[i].style = "display:none";
    }
}


/**
 * 建立npo資訊列表 in modal
 * @param {*} res 
 */
function makeNpoList(res) {
    res.forEach(function (item, i) {
        const fragment = document.createDocumentFragment();
        const tr = document.createElement('tr');
        tr.setAttribute('data-trId', item.id);

        // 序號
        const numberEl = document.createElement('td');
        numberEl.setAttribute('scope', 'row');
        numberEl.textContent = i + 1;
        tr.appendChild(numberEl);

        // 方式
        const methodEl = document.createElement('td');
        let method = item.method;
        switch (method) {
            case 1: {
                method = "匯款";
                break;
            }
            case 2: {
                method = "刷卡";
                break;
            }
            default: {
                method = "其他";
                break;
            }
        }
        methodEl.textContent = method;
        tr.appendChild(methodEl);

        // 機構名稱
        const nameEl = document.createElement('td');
        nameEl.textContent = item.name;
        tr.appendChild(nameEl);

        // 帳號or連結
        const accEl = document.createElement('td');
        if (item.account !== null) {
            accEl.textContent = item.account;
        } else if (item.pay_on_line !== null) {
            const payLink = `<a href="${item.pay_on_line}" target="_blank">線上付款</a>`;
            accEl.innerHTML = payLink;
        }
        tr.appendChild(accEl);

        // 銀行代碼+金融機構
        const codeEl = document.createElement('td');
        if (item.code !== null) {
            codeEl.textContent = item.code;
        }
        tr.appendChild(codeEl);

        const bankEl = document.createElement('td');
        if (item.bank !== null) {
            bankEl.textContent = item.bank;
        }
        tr.appendChild(bankEl);

        // 表單連結
        const formEl = document.createElement('td');
        if (item.form_link !== null) {
            const formLink = `<a href="${item.form_link}" target="_blank">前往</a>`;
            formEl.innerHTML = formLink;
        }
        tr.appendChild(formEl);

        // icons
        const iconEl = document.createElement('td');
        const icons = `
        <i class="fa-solid fa-pen mx-2" data-id=${item.id}></i>
        <i class="fa-solid fa-trash" data-id=${item.id}></i>`;
        iconEl.innerHTML = icons;
        tr.appendChild(iconEl);
        fragment.appendChild(tr);
        npoTbody.appendChild(fragment);
    })
}


function clearNpoModal() {
    $("#npoTbody").empty(); //清空所有子元素和內容
}