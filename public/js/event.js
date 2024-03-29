var yearTag = document.getElementById('yearTag');

var main = document.getElementById('main');

var group = document.getElementById('group');

var today = new Date();

var thisYear = today.getFullYear(); // 2024

var thisMonth = today.getMonth(); // 2 


$(document).ready(function () {

    $.ajaxSetup({
        header: {
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



    $(document)
        .on('click', '#searchIcon', function () {
            let selectedYear = yearSel.value;
            $.ajax({
                url: "/api/event/" + selectedYear,
                method: "GET",
                success: function (res) {
                    yearTag.innerText = res.year;
                    main.innerHTML = '';
                    putInValies(res);
                },
                error: function (err) {
                    alert(err.responseJSON.message);
                }
            });
        })


    getEvents();
})

/**
 * get all event data
 */
function getEvents() {

    $.ajax({
        url: "/api/event/" + thisYear,
        method: "GET",
        success: function (res) {
            yearTag.innerText = res.year;
            putInValies(res);
            makeYearListOpt(res);
        },
        error: function (err) {
            console.log(err.responseJSON.message);
        }
    });
}


/**
 * Create elements & put value into ele
 */
function putInValies(res) {
    let fragment = document.createDocumentFragment();

    for (let g = 1; g <= 2; g++) {
        let group = document.createElement('div');
        group.classList.add('group', 'col-lg-6', 'col-md-12', 'col-sm-12');

        for (let i = 1; i <= 6; i++) {
            let monthGroup = document.createElement('div');
            monthGroup.className = 'monthGroup';

            let month = document.createElement('div');
            month.className = 'month';
            monthGroup.appendChild(month);

            let p = document.createElement('p');
            p.textContent = (g == 1) ? i : i + 6;
            month.appendChild(p);

            let textGroup = document.createElement('div');
            textGroup.classList.add('row', 'textGroup');

            for (let j = 1; j <= 2; j++) {
                let text = document.createElement('div');
                text.classList.add('text', 'col');

                for (let k = 1; k <= 3; k++) {
                    // class
                    let tt = document.createElement('div');
                    tt.className = 'tt';
                    // id
                    id = setIdForTt(g, i, j, k);
                    tt.id = id;
                    // text
                    tt.innerText = setResValForTt(res, id);
                    text.appendChild(tt);

                    let dotLine = document.createElement('div');
                    dotLine.className = 'dotLine';
                    text.appendChild(dotLine);
                }
                textGroup.appendChild(text);
            }

            monthGroup.appendChild(month);
            monthGroup.appendChild(textGroup);
            group.appendChild(monthGroup);
            fragment.appendChild(group);
        }
    }
    main.appendChild(fragment);
}



/**
 * 為每個tt欄位設定id
 * @param {*} g 半年group迴圈數(<=2)
 * @param {*} i 月份迴圈數(<=6)
 * @param {*} j 半個月份迴圈數(<=2)
 * @param {*} k dotLine迴圈數(<=3)
 */
function setIdForTt(g, i, j, k,) {

    if (g == 1) {
        i = (i < 10) ? "0" + i : String(i);
    } else {
        i = (i < 10) ? "0" + (i + 6) : String(i + 6);
    }
    k = (j == 1) ? String(k) : String(k + 3);
    id = thisYear + i + k;
    return id;
}


/**
 * 
 * @param {*} res 
 * @param {*} id 每一格輸入欄的id(年+月+輸入欄序號 ex.2024021)
 * @returns 
 */
function setResValForTt(res, id) {
    let idYear = id.substr(0, 4);
    let idMonth = id.substr(4, 2);
    let idOrdinal = id.substr(6, 1);
    let data = res.data[idMonth][idOrdinal] ?? '';

    return data;
}

