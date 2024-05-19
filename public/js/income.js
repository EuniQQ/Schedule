var addBtn = document.getElementById('addBtn');
var npoBtn = document.getElementById('npoBtn');


$(document).ready(function () {

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
        $("#npoTbody").empty();  //清空所有子元素和內容
    })


/**
 * 建立npo資訊列表 in modal
 * @param {*} res 
 */
function makeNpoList(res) {
    res.forEach(function (item, i) {
        let fragment = document.createDocumentFragment();
        let tr = document.createElement('tr');

        let numberEl = document.createElement('td');
        numberEl.setAttribute('scope', 'row');
        numberEl.textContent = i + 1;
        tr.appendChild(numberEl);

        let methodEl = document.createElement('td');
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

        let nameEl = document.createElement('td');
        nameEl.textContent = item.name;
        tr.appendChild(nameEl);

        let accEl = document.createElement('td');
        accEl.textContent = item.account;
        tr.appendChild(accEl);

        let bankEl = document.createElement('td');
        bankEl.textContent = item.bank;
        tr.appendChild(bankEl);

        let formEl = document.createElement('td');
        if (item.form_link !== null) {
            formAtag = document.createElement('a');
            formAtag.setAttribute('href', item.form_link);
            formAtag.textContent = "網址";
            formEl.appendChild(formAtag);
        }
        tr.appendChild(formEl);

        let iconEl = document.createElement('td');
        const icons = `
        <i class=" fa-solid fa-pen mx-2" data-id=${item.id}></i>
        <i class="fa-solid fa-trash" data-id=${item.id}></i>`;
        iconEl.innerHTML = icons;
        tr.appendChild(iconEl);
        fragment.appendChild(tr);

        const npoTbody = document.getElementById('npoTbody');
        npoTbody.appendChild(fragment);
    })
}