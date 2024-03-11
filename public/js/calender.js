var addModal = document.getElementById('addModal');
var openModalBtn = document.getElementsByClassName('plusIcon');
var closeBtn = document.getElementsByClassName('btn-close');
var calender = document.getElementsByClassName('calender');
var stickerPre = document.getElementById("stickerPre");
var mondalForm = document.getElementById("modalForm");
var modalTitle = document.getElementById("addModalLabel");
var mcStartInput = document.getElementById('mcStart');
var mcEndInput = document.getElementById('mcEnd');
var saveAddBtn = document.getElementById("addModalSubmit");
var saveChgBtn = document.getElementById("editModalSubmit");
var delBtn = document.getElementById("delModalSubmit");
var stickerInp = document.getElementById("stickerInp");
var tagColorInp = document.querySelector("#addModal input[name='tag_color']")
var dateInp = document.getElementById("modalDateInp");
var dayNum = document.getElementsByClassName('day');
var sticker_pre = $("#stickerPre").prop('src');


$(document).ready(function () {


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer' + getApiToken()
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
     * 取得特定cookie值
     */
    function getCookie(name) {
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length == 2) {
            return parts.pop().split(";").shift();
        }
    }



    getWeatherType();
    /**
     * 取得天氣型態api
     */
    function getWeatherType() {

        $.ajax({
            url: "/api/calender/weather/type",
            method: "GET",
            success: function (res) {
                res.forEach(function (val, i) {
                    if (i % 2 === 0) {
                        let endTime = res[i].endTime;  //2024-03-01 12:00:00
                        let day = endTime.slice(0, 10);
                        let weatherCode = res[i].elementValue[1].value;
                        chgWeatherCodeToIcon(weatherCode, day);
                    }
                });
                getWeatherDes();
            }, error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
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
            isThunderstorm: ['15', '16', '17', '18', '21', '22', '33', '34', '35',
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

        let res = "/storage/img/weather/" + weatherIcon[key];
        let type = "icon"
        showWeather(type, day, res);
    }



    /**
     * 取得天氣敘述api
     */
    function getWeatherDes() {
        $.ajax({
            url: "/api/calender/weather/des",
            method: "GET",
            success: function (res) {

                res.forEach(function (val, i) {

                    if (i % 2 === 0) {
                        let endTime = res[i].endTime;  //2024-03-01 18:00:00
                        let day = endTime.slice(0, 10);
                        let des = res[i].elementValue[0].value;

                        if (i < 6) {
                            // des ='陰有雨。降雨機率 100%。溫度攝氏11至11度。
                            let parts = des.split(' ')[1];
                            let present = parts.split('。')[0];
                            let tempParts = parts.split('。')[1];
                            let temperature = tempParts.substring(4).replace("至",
                                "-").replace("度", "℃");
                            let res = present + "  " + temperature;
                            let type = "des";
                            showWeather(type, day, res);

                        } else {
                            // des='晴時多雲。溫度攝氏18至30度。稍有寒意至悶熱。
                            let tempParts = des.split("。")[1];
                            let temperature = tempParts.substring(4).replace("至",
                                "-").replace("度", "℃");
                            let res = temperature;
                            let type = "des"
                            showWeather(type, day, res);
                        }
                    }
                });
            }, error: function (err) {
                // console.log(err.responseJSON.message);
            }
        })
    }



    /**
     * 
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

        if (type == 'des') {
            const p = document.createElement('p');
            p.textContent = res;
            p.className = "wthText";
            endLayer.appendChild(p);

        } else {
            const img = document.createElement('img');
            img.src = res;
            img.className = 'sticker';
            endLayer.appendChild(img);
        }
    }



    /**
     * 監聽modal表單元素的變更事件(用於新增)
     */
    var changes = new FormData();
    addModal.addEventListener('change', function (e) {
        const target = e.target;
        const date = dateInp.value;
        changes.append('date', date);
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




    // mcStartInput.addEventListener('click', function (e) {
    // if (e.target.checked) {
    // e.target.checked = false;
    // }
    // });

    // mcEndInput.addEventListener('click', function (e) {
    // if (e.target.checked) {
    // e.target.checked = false;
    // }
    // });




    /**
     * 點擊modal以外處，關閉modal
     */
    window.onclick = evt => {
        if (evt.target == addModal) {
            addModal.style.display = "none";
        }
    }


    /**
     * 上傳sticker同時預覽
     */
    stickerInp.onchange = evt => {
        const [file] = stickerInp.files
        stickerPre.src = URL.createObjectURL(file);
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

            if (calenderId === '') {
                modalTitle.innerText = "新增行程";
                saveChgBtn.style.display = "none";
                saveAddBtn.style.display = "block";

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
            let errGroup = document.getElementById('errGroup');
            if (errGroup !== null) {
                errGroup.remove();
            }

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
                    changes = new FormData();
                },
                error: function (err) {
                    showErrMsgFromModal(err);
                }
            })
        })



        .on("click", "#editModalSubmit", function (e) {
            let id = this.getAttribute('data-id');
            let errGroup = document.getElementById('errGroup');
            if (errGroup !== null) {
                errGroup.remove();
            }

            let date = $("#modalDateInp").val();
            let birthday_person = $("#bthdGuy").val();
            let mc = $("#addModal input[name='mc']:checked").val();
            let plan_time = $("#planTime").val();
            let plan = $("#plan").val();
            let tag_to = $("#tagTo").val();
            let tag_title = $("#tagTitle").val();
            let tag_color = $("#tagColor").val();
            let sticker = $("#stickerInp")[0].files[0] !== undefined ? $("#stickerInp")[0].files[0] : null;
            let photos_link = $("#photosLink").val();

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
            data.append('sticker_pre', sticker_pre);
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
                    changes = new FormData();
                },
                error: function (err) {
                    showErrMsgFromModal(err);
                }
            })
        })




        /**
         * 關閉modal
         */
        .on("click", ".closeModal", function (e) {
            addModal.style.display = "none";
        })

        /**
         * 刪除單日行程
         */
        .on("click", "#delModalSubmit", function (e) {
            const calenderId = e.target.getAttribute('data-id');
            const date = $("#addModal input[name='date']").val();
            const userId = $("#addModal input[name='user_id']").val();
            addModal.style.display = "none";
            $.ajax({
                url: "/api/daylySchedule/" + calenderId,
                type: "POST",
                data: {
                    _method: "DELETE",
                    userId: userId
                },
                success: function (res) {
                    alert(res.message);
                    updateDailySchedule(date);
                },
                error: function (err) {
                    console.log(err.responseJSON.message);
                }
            })
        })

        /**
         * 搜尋特定日期
         */
        .on("click", "#searchIcon", function () {
            const searchYear = document.getElementById
                ('conYearSel');
            const searchMon = document.getElementById
                ('searchMonth');
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

        .on("click", "#clearStickerBtn", function (e) {
            $("#stickerPre").prop('src', '');
            sticker_pre = ''; // 全域變數用於upate api
        })

        .on("click", "#modalReset", function (e) {
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