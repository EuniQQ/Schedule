// calender
var calMain = document.getElementById('calMain');
var calender = document.getElementsByClassName('calender');
var dayNum = document.getElementsByClassName('day');
// modal
var addModal = document.getElementById('addModal');
var stickerInp = document.getElementById('stickerInp');
var stickerPre = document.getElementById('stickerPre');
var stickerPreSrc = stickerPre.getAttribute('src');
var mondalForm = document.getElementById('modalForm');
var modalTitle = document.getElementById('addModalLabel');
var dateInp = document.getElementById('modalDateInp');
var tagColorInp = document.querySelector('#addModal input[name="tag_color"]')
var mcStartInput = document.getElementById('mcStart');
var mcEndInput = document.getElementById('mcEnd');
var openModalBtn = document.getElementsByClassName('plusIcon');
var closeBtn = document.getElementsByClassName('btn-close');
var saveAddBtn = document.getElementById('addModalSubmit');
var saveChgBtn = document.getElementById('editModalSubmit');
var delBtn = document.getElementById("delModalSubmit");
// header
var searchYear = document.getElementById('yearSel');
var searchMon = document.getElementById('searchMonth');
var headerMonth = document.getElementById('headerMonth');
// left side 
var WesternYear = document.getElementById('calWesYear');
var hebrewYear = document.getElementById('calHebrewYear');
var calIncome = document.getElementById('calIncome');
var calExp = document.getElementById('calExp');
var calBal = document.getElementById('calBal');
// offCAnvas
var mainImgPre = document.getElementById('mainImgPre');
var headerImgPre = document.getElementById('headerImgPre');
var footerImgPre = document.getElementById('footerImgPre');
var calColorInp = document.getElementById('calColorInp');
var ftColorInp = document.getElementById('ftColorInp');
var offcanvasSmt = document.getElementById('offcanvasSmt');
resetStyleBtn = document.getElementById('resetStyleBtn');
// else
var footer = document.getElementById('footer');
var sideBar = document.getElementById('sideBar');
var headerMonth = document.getElementById('headerMonth');
var iconSec = document.getElementById('iconSec');
var monthSec = document.getElementById('monthSec');


$(document).ready(function () {


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + getApiToken()
        }
    });

    getCalender();


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



    function getCalender() {
        let currentUrl = window.location.href;
        let thisYear = getDay().thisYear;
        let thisMonth = getDay().thisMonth;
        let splittedUrl = currentUrl.split('/');
        let year = splittedUrl[4];
        let month = splittedUrl[5];
        let argYear = year ? year : thisYear;
        let argMonth = month ? month : thisMonth; // 含前導0
        $.ajax({
            url: "/api/calender/" + argYear + '/' + argMonth,
            method: "GET",
            dataType: "json",
            success: function (res) {
                createCalenderElements(res);
                createDecorateImgs(res);
                putInValues(res);
                settingStyle(res);
                makeYearListOpt(res);
            }, error: function (err) {

            }
        })
    }

    
    /**
     * 新增、修改、刪除後更新月曆
     */
    function renewCalender() {
        let currentUrl = window.location.href;
        let thisYear = getDay().thisYear;
        let thisMonth = getDay().thisMonth;
        let splittedUrl = currentUrl.split('/');
        let year = splittedUrl[4];
        let month = splittedUrl[5];
        let argYear = year ? year : thisYear;
        let argMonth = month ? month : thisMonth;
        $.ajax({
            url: "/api/calender/" + argYear + '/' + argMonth,
            method: "GET",
            dataType: "json",
            success: function (res) {
                calMain.innerHTML = "";
                createCalenderElements(res);
            }, error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
    }



    function createCalenderElements(res) {

        let fragment = document.createDocumentFragment();
        res.calender.forEach(function (item, i) {
            // 單一格子
            let singleDay = document.createElement('div');
            singleDay.id = "i" + item.fullDate;
            singleDay.className = 'singleDay';
            singleDay.setAttribute('data-id', item.id);
            if (res.month == res.thisMonth && item.date == res.today) {
                singleDay.style.backgroundColor = 'rgba(255, 238, 150, 1)';
            }
            // first layer START
            let FirLayer = document.createElement('div');
            FirLayer.className = 'firstLayer';
            // first layer : date num
            let dayNum = document.createElement('p');
            dayNum.className = 'day';
            if (item.mc == 1 || item.mc == 2) {
                dayNum.classList.add('mc');
            }
            if (item.week == '六') {
                dayNum.classList.add('sat');
            }
            if (item.week !== '六' && (item.week == '日' || item.isHoliday == true)) {
                dayNum.classList.add('sun');
            }
            dayNum.textContent = item.date;
            FirLayer.appendChild(dayNum);

            // first layer : bthd person
            let bthdSet = document.createElement('div');
            bthdSet.classList.add('bthdSet', 'd-flex');
            let isBthdPersonExist = Object.keys(item).includes('birthday_person');
            if (isBthdPersonExist == true) {
                if (!!item.birthday_person) {
                    bthdSet.innerHTML = '<i class="fa-solid fa-cake-candles" style="color:#c200bb">&nbsp;</i>';
                    let bthdPerson = document.createElement('span');
                    bthdPerson.className = 'bthd';
                    bthdPerson.innerText = item.birthday_person;
                    bthdSet.appendChild(bthdPerson);
                }
            }
            FirLayer.appendChild(bthdSet);
            singleDay.appendChild(FirLayer);
            // first layer END

            // middle layer START
            let midLayer = document.createElement('div');
            midLayer.className = 'middleLayer';

            // middle layer : hover plusIcon
            if (item.date) {
                let plusIcon = document.createElement('i');
                plusIcon.id = item.fullDate;
                plusIcon.classList.add('plusIcon', 'fa-solid', 'fa-circle-plus', 'fa-2xl');
                plusIcon.setAttribute('src', '/images/plus_icon.svg');
                plusIcon.setAttribute('data-id', item.id);
                midLayer.appendChild(plusIcon);
            }

            // middle layer : description
            let isDesExist = Object.keys(item).includes('description');
            if (isDesExist == true && item.description) {
                let calDes = document.createElement('li');
                calDes.className = 'calDes';
                calDes.innerText = item.description;
                midLayer.appendChild(calDes);
            }

            // middle layer : plan
            let isPlanExist = Object.keys(item).includes('plan');
            if (isPlanExist == true && !!item.plan) {
                let calPlan = document.createElement('li');

                calPlan.className = 'calPlan';
                calPlan.textContent = !!item.plan_time ? item.plan_time + ' ' + item.plan : item.plan;
                midLayer.appendChild(calPlan);
            }
            singleDay.appendChild(midLayer);
            // middle layer END

            // end layer START
            let endLayer = document.createElement('div');
            endLayer.className = 'endLayer';
            endLayer.id = 'e' + item.fullDate;

            if (!!item.tag_color) {
                endLayer.style.backgroundColor = item.tag_color;
            }

            if (!!item.sticker) {
                let sticker = document.createElement('img');
                sticker.className = 'sticker';
                sticker.setAttribute('src', item.sticker);
                endLayer.appendChild(sticker);
            }

            if (!!item.tag_title) {
                let tagTitle = document.createElement('p');
                tagTitle.className = 'wthText';
                tagTitle.textContent = item.tag_title;
                endLayer.appendChild(tagTitle);
            }
            singleDay.appendChild(endLayer);
            // end layer END

            fragment.appendChild(singleDay);
        })
        calMain.appendChild(fragment);
        // 取得天氣型態
        getWeatherType(res);

    }


    /**
     * 取得天氣型態api
     */
    function getWeatherType(res) {
        let weatherType = res.weatherType;
        weatherType.forEach(function (val, i) {
            if (i % 2 === 0) {
                let endTime = weatherType[i].endTime;  //2024-03-01 12:00:00
                let day = endTime.slice(0, 10);
                let weatherCode = weatherType[i].elementValue[1].value;
                chgWeatherCodeToIcon(weatherCode, day);
            }
        });
        getWeatherDes(res);
    }



    /**
     * 根據天氣代號轉換為圖片
     */
    function chgWeatherCodeToIcon(weatherCode, day) {
        const weatherTypes = {
            isSunny: ['01', '24'],
            isSunnyWithCloudy: ['02', '03', '25', '26'],
            isCloudy: ['04', '05', '06', '07', '27', '28'],
            isSunnyWithRain: ['19'],
            isCloudyWithRain: ['08', '09', '10', '12', '13', '20', '29', '30',
                '31', '32'],
            isThunderstorm: ['15', '16', '17', '18', '21', '22', '33', '34',
                '35',
                '36', '41'],
            isSnowing: ['23', '37', '42'],
            isRainny: ['11', '14', '38', '39']
        }

        const typeArr = Object.entries(weatherTypes);
        const key = typeArr.find(([weatherType, weatherCodes]) => weatherCodes.
            includes(weatherCode))[0];

        const weatherIcon = {
            isSunny: "sun.png",
            isSunnyWithCloudy: "clear-sky.png",
            isCloudy: "cloud.png",
            isSunnyWithRain: "cloudy.png",
            isCloudyWithRain: "rainy-day.png",
            isThunderstorm: "storm.png",
            isSnowing: "snow.png",
            isRainny: "heavy-rain.png"
        }

        let res = "/images/weather/" + weatherIcon[key];
        let type = "icon"
        showWeather(type, day, res);
    }



    /**
     * 取得天氣敘述api
     */
    function getWeatherDes(res) {
        let weatherDes = res.weatherDes;
        weatherDes.forEach(function (val, i) {

            if (i % 2 === 0) {
                let endTime = weatherDes[i].endTime;  //2024-03-01 18:00:00
                let day = endTime.slice(0, 10);
                let des = weatherDes[i].elementValue[0].value;

                if (i < 6) {
                    // des ='陰有雨。降雨機率 100%。溫度攝氏11至11度。
                    let parts = des.split(' ')[1];
                    let present = parts.split('。')[0];
                    let tempParts = parts.split('。')[1];
                    let temperature = tempParts.substring(4).replace("至",
                        "-").replace("度", "℃");
                    let desRes = present + "  " + temperature;
                    let type = "des";
                    showWeather(type, day, desRes);
                } else {
                    // des='晴時多雲。溫度攝氏18至30度。稍有寒意至悶熱。
                    let tempParts = des.split("。")[1];
                    let temperature = tempParts.substring(4).replace("至",
                        "-").replace("度", "℃");
                    let desRes = temperature;
                    let type = "des"
                    showWeather(type, day, desRes);
                }
            }
        });
    }



    /**
     * 顯示天氣資訊
     * @param {*} type 資料類型
     * @param {*} day 資料日期
     * @param {*} res 天氣資訊
     */
    function showWeather(type, day, res) {
        let parts = day.split('-');
        let year = parts[0];
        let month = parts[1];
        let date = parts[2];
        let newId = "e" + year + month + date;
        const endLayer = document.getElementById(newId);
        if (!!endLayer) {
            const childElements = endLayer.children;
            if (type == 'des') {
                const p = document.createElement('p');
                p.textContent = res;
                p.className = "wthText";
                endLayer.appendChild(p);

            } else {
                const img = document.createElement('img');
                img.src = res;
                img.className = 'sticker';
                if (childElements.length > 0) {
                    Array.from(childElements).forEach(el => {
                        el.style.display = 'none';
                    })
                }
                endLayer.appendChild(img);
            }
        }
    }



    function createDecorateImgs(res) {
        // main img
        let mainImg = document.createElement('img');
        mainImg.id = 'mainImg';
        if (!!res.style.main_img) {
            mainImg.setAttribute('src', res.style.main_img);
        } else {
            mainImg.setAttribute('src', '/images/QQsticker.png');
        }
        sideBar.insertBefore(mainImg, sideBar.firstChild);

        // header img
        if (!!res.style.header_img) {
            let headerImg = document.createElement('img');
            headerImg.setAttribute('src', res.style.header_img);
            headerImg.id = 'headerImg';
            monthSec.insertBefore(headerImg, iconSec);
        }

        // footer img
        if (!!res.style.footer_img) {
            let footerImg = document.createElement('img');
            footerImg.setAttribute('src', res.style.footer_img);
            footerImg.id = 'footerImg';
            sideBar.appendChild(footerImg);
        }
    }


    /**
     * getCalender API 賦值
     * @param {*} res getCalender API res
     */
    function putInValues(res) {
        // left side 
        WesternYear.textContent = res.year;
        hebrewYear.textContent = res.hebrewYear;
        calIncome.textContent = res.income;
        calExp.textContent = res.expense;
        calBal.textContent = res.balance;

        // offCAnvas
        mainImgPre.setAttribute('src', res.style.main_img);
        headerImgPre.setAttribute('src', res.style.header_img);
        footerImgPre.setAttribute('src', res.style.footer_img);
        offcanvasSmt.setAttribute('data-year', res.year);
        offcanvasSmt.setAttribute('data-month', res.month);
        // offcanvasSmt.setAttribute('data-userId', res.userId);
        offcanvasSmt.setAttribute('data-id', res.style.id);
        resetStyleBtn.setAttribute('data-id', res.style.id);
        calColorInp.value = res.style.bg_color;
        ftColorInp.value = res.style.footer_color;

        // month
        headerMonth.textContent = res.month;

        // footer month link
        const footerAtags = document.getElementsByClassName('footerAtag');
        Array.from(footerAtags).forEach(function (tag, i) {
            let month = (i + 1) < 10 ? '0' + (i + 1) : (i + 1);
            let href = '/calender/' + res.year + '/' + month;
            tag.setAttribute('href', href);

            if (month == headerMonth.textContent) {
                tag.style.color = '#f3ef0a';
                tag.style.textDecoration = 'underline';
            }
        });
    }


    /**
     * 設定畫面視覺色
     * @param {*} res = get calender api res
     */
    function settingStyle(res) {
        footer.style.backgroundColor = res.style.footer_color;

        // calender background color
        let rule = '.singleDay:nth-child(odd) {background-color :' +
            res.style.bg_color + ';}';
        let styleElement = document.createElement('style');
        styleElement.textContent = rule;
        document.head.appendChild(styleElement);
    }


    /**
     * 監聽modal表單元素的變更事件(用於新增)
     */
    var changes = new FormData();
    addModal.addEventListener('change', function (e) {
        const target = e.target;
        const date = dateInp.value;
        // changes.append('date', date);
        if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
            if (target.type === 'file') {
                changes.append(target.name, target.files[0]);
            } else {
                // 就算欄位值是空字串也轉為null存進FormData
                // const value = target.value.trim() === '' ? null : target.value;
                changes.append(target.name, target.value);
            }
        }
    })


    /**
     * 監聽modal sticker input(preview)
     */
    stickerInp.addEventListener('change', function (e) {
        previewSelect(e);
    })


    /**
     * 點擊modal以外處，關閉modal
     */
    window.onclick = evt => {
        if (evt.target == addModal) {
            addModal.style.display = "none";
        }
    }




    $(document)
        .on("click", ".plusIcon", function (e) {
            addModal.style.display = "block";
            const plusIconId = e.target.id;
            const calenderId = e.target.getAttribute('data-id');
            const formattedDate = formatDate(plusIconId);

            $("#addModal input[name='date']").val(plusIconId);
            $("#addModal input[name='id']").val(calenderId);
            $("#addModal input[name='tag_from']").val(formattedDate);

            removeErrMsg();
            if (calenderId === '') {
                modalTitle.innerText = "新增行程";
                saveChgBtn.style.display = "none";
                saveAddBtn.style.display = "block";
                saveAddBtn.setAttribute('data-date', plusIconId);
            } else {
                modalTitle.innerText = "修改行程";
                getModalContent(calenderId);
                saveAddBtn.style.display = "none";
                saveChgBtn.style.display = "block";
                saveChgBtn.setAttribute('data-id', calenderId);
                delBtn.setAttribute('data-id', calenderId);
                saveChgBtn.setAttribute('data-date', plusIconId);
            }
        })


        .on("click", "#addModalSubmit", function () {
            const date = saveAddBtn.getAttribute('data-date');
            changes.append('date', date);

            $.ajax({
                url: "/api/calender",
                method: "POST",
                data: changes,
                processData: false,
                contentType: false,
                success: function (res) {
                    $("#addModal").hide();
                    $(".modal-backdrop").remove();
                    alert("新增成功");
                    renewCalender();
                    changes = new FormData();
                },
                error: function (err) {
                    showErrMsgFromModal(err);
                }
            })
        })


        .on("click", "#editModalSubmit", function (e) {
            let id = this.getAttribute('data-id');
            let date = $("#modalDateInp").val();
            let birthday_person = $("#bthdGuy").val();
            let mc = $("#addModal input[name='mc']:checked").val();
            let plan_time = $("#planTime").val();
            let plan = $("#plan").val();
            let tag_to = $("#tagTo").val();
            let tag_title = $("#tagTitle").val();
            let tag_color = $("#tagColor").val();
            let sticker = $("#stickerInp")[0].files[0] !== undefined ? $
                ("#stickerInp")[0].files[0] : '';
            let photos_link = $("#photosLink").val();

            // 判斷欄位是否為全空
            let isAllFalse = [
                birthday_person, mc, plan_time, plan, tag_to, tag_title,
                sticker, photos_link
            ].every(field => field === undefined || field === '');

            if (isAllFalse === true) {
                alert('請填入表單資料或直接刪除');

            } else {
                const data = new FormData;
                data.append('date', date);
                data.append('birthday_person', birthday_person);
                data.append('mc', mc);
                data.append('plan_time', plan_time);
                data.append('plan', plan);
                data.append('tag_to', tag_to);
                data.append('tag_title', tag_title);
                data.append('tag_color', tag_color);
                data.append('sticker', sticker);
                data.append('sticker_pre', stickerPreSrc);
                data.append('photos_link', photos_link);
                $.ajax({
                    url: "/api/calender/" + id,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $("#addModal").hide();
                        $(".modal-backdrop").remove();
                        alert("更新成功");
                        renewCalender();
                        changes = new FormData();
                    },
                    error: function (err) {
                        showErrMsgFromModal(err);
                    }
                })
            }
        })



        /**
         * 關閉modal
         */
        .on("click", ".closeModal", function (e) {
            addModal.style.display = "none";
            $("#modalDateInp").val('');
            $("#bthdGuy").val('');
            $("#addModal input[name='mc']:checked").val('');
            $("#planTime").val('');
            $("#plan").val('');
            $("#tagTo").val('');
            $("#tagTitle").val('');
            $("#tagColor").val('');
            $("#stickerInp")[0].files[0] = null;
            $("#stickerPre").prop('src', '');
            $("#photosLink").val('');
        })


        /**
         * 刪除單日行程
         */
        .on("click", "#delModalSubmit", function (e) {
            const calenderId = e.target.getAttribute('data-id');
            e.preventDefault();
            if (window.confirm('是否確定刪除此日程?')) {
                $.ajax({
                    url: "/api/daylySchedule/" + calenderId,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                    },
                    success: function (res) {
                        $("#addModal").hide();
                        $(".modal-backdrop").remove();
                        alert(res.message);
                        renewCalender();
                    },
                    error: function (err) {
                        console.log(err.responseJSON.message);
                    }
                })
            }
        })

        /**
         * 搜尋特定日期
         */
        .on("click", "#searchIcon", function () {
            let year = searchYear.value;
            console.log(year);
            let month = searchMon.value;
            console.log(month);
            let url = "/calender/" + year + "/" + month;
            window.location = url;
        })

        .on("click", "#clearPlanTime", function () {
            $("#planTime").val('');
        })

        .on("click", "#clearStickerBtn", function () {
            $("#stickerPre").prop('src', '');
            stickerPreSrc = ''; // 全域變數用於upate api
        })

        .on("click", "#clearTagSec", function () {
            $("#tagTo").val('');
            $("#tagTitle").val('');
            $("#tagColor").val('');
        })

        .on("click", "#modalReset", function () {
            $("#stickerPre").prop('src', '');
        })

})



/**
 * 格式化日期
 * 以正則表達式將"yyyymmdd"轉為"yyyy-mm-dd"
 */
function formatDate(dateStr) {
    const res = dateStr.replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3');
    return res;
}



/**
 * 更新已刪除單格內容
 * @param {*} date 
 */
function updateDailySchedule(date) {
    console.log('DATE = ' + date);
    const element = document.getElementById("i" + date);
    console.log('element=' + element);
    if (element) {
        const bthd = element.querySelector(".bthd");
        const middleLayer = element.querySelector(".middleLayer");
        const calPlan = middleLayer.querySelector(".calPlan");
        const endLayer = element.querySelector(".endLayer");
        const bthdSet = element.querySelector(".bthdSet");
        bthdSet.innerHTML = '';
        endLayer.innerHTML = '';
        endLayer.setAttribute('style', '');
        if (middleLayer) {
            middleLayer.removeChild(calPlan);
        }
    }
}



/**
 * 取得單筆modal資料
 * @param {*} calenderId 
 */
function getModalContent(calenderId) {
    $.ajax({
        url: "/api/daylySchedule/" + calenderId,
        method: "GET",
        success: function (res) {
            $("#addModal input[name='birthday_person']").val(res.
                birthday_person);
            $("#addModal input[name='plan_time']").val(res.plan_time);
            $("#addModal input[name='plan']").val(res.plan);
            $("#addModal input[name='tag_from']").val(res.tag_from);
            $("#addModal input[name='tag_to']").val(res.tag_to);
            $("#addModal input[name='tag_title']").val(res.tag_title);
            $("#addModal input[name='tag_color']").val(res.tag_color);
            $("#addModal input[name='photos_link']").val(res.photos_link);
            $("#stickerPre").attr('src', res.sticker);
            $("#stickerPre").data('id', res.id);

            if (res.mc == 1) {
                $("#mcStart").prop('checked', true)
            }

            if (res.mc == 2) {
                $("#mcEnd").prop('checked', true)
            }
        },
        error: function (error) {
            alert(error);
        }
    })
}


function clearInputTime() {
    document.getElementById('planTime').value = ' ';
}
