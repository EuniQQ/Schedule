var dailyTexts = document.getElementsByClassName("dailyText");

var toggleSwitch = document.getElementById("flexSwitchCheckDefault");

var saveAddBtn = document.getElementById("saveAdd");

var saveChgBtn = document.getElementById("saveEdit");

var mainContent = document.getElementById("main");

var addBtn = document.getElementById("addBtn");

var editInps = document.getElementsByClassName("editBtn");

var modal = document.getElementById("editModal");

var modalTitle = document.getElementById("editModalLabel");



$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer' + getApiToken()
        }
    });

    getJournals();

});



$(document)
    .on("click", "#addBtn", function (e) {
        modalTitle.innerHTML = "新增日記";
        saveAddBtn.style.display = "block";
        saveChgBtn.style.display = "none";
    })

    .on("click", ".editBtn", function (e) {
        modalTitle.innerHTML = "修改日記";
        journalId = e.target.getAttribute("data-id");
        saveAddBtn.style.display = "none";
        saveChgBtn.style.display = "block";
        getEditModelData(journalId);
    })

    .on("change", ".upload", function (e) {
        previewSelect(e);
    })

    /**
     * clean input val when close modal
     */
    .on("click", ".closeEdit", function () {
        const textInps = document.querySelectorAll('input[type="text"]');
        const uploadInps = document.querySelectorAll('input[type="upload"]');
        const imgPres = document.querySelectorAll(".imgSet img");
        const hiddenInp = document.querySelector('input[type="hidden"]');
        const dateInp = document.querySelector('input[type="date"]');
        const contentInp = document.getElementById("moContent");
        const linkInp = document.querySelector('input[type="url"]');

        textInps.forEach(textInp => {
            textInp.value = '';
        });

        uploadInps.forEach(uploadInp => {
            uploadInp = '';
        });

        imgPres.forEach(img => {
            img.src = '';
        })

        hiddenInp.value = dateInp.value = contentInp.value = linkInp.value = '';
    })

    /**
     * create journal AJAX
     */
    .on("click", "#saveAdd", function (e) {

        $.ajax({
            url: "/api/journal",
            method: "POST",
            data: changes,
            processData: false, // 避免jQuery對數據進行處理
            contentType: false, // 告訴jQuery不要設置 Content-Type header，因FormData會自動處理
            success: function (res) {
                $("#editModal").hide();
                $(".modal-backdrop").remove();
                window.location.reload();
            },
            error: function (err) {
                let errMessage = err.responseJSON.message;
                let messages = errMessage.split('\n');  // 將err照換行符分成多
                行
                const modalBody = document.getElementById('modalBody');

                messages.forEach(message => {
                    let showErr = document.createElement('div');
                    showErr.textContent = errMessage;
                    showErr.className = "alert alert-danger";
                    modalBody.insertBefore(showErr, modalBody.firstChild);
                })
            }
        })
        changes = new FormData();
    })


    /**
     * update journal AJAX
     */
    .on("click", "#saveEdit", function (e) {
        let id = $("#saveEdit").data('id');
        const moDate = document.getElementById('moDate');
        const moTitle = document.getElementById('moTitle');
        const moContent = document.getElementById('moContent');

        if (moDate.value == '' || moTitle.value == '' || moContent.value == '') {
            alert("日期、標題、內文為必填");
        }
        else {
            $.ajax({
                url: "/api/journal/" + id,
                method: "POST",
                data: changes,
                processData: false,
                contentType: false,
                success: function (res) {
                    $("#editModal").hide();
                    $(".modal-backdrop").remove();
                    getJournals();
                },
                error: function (err) {
                    console.log(err.responseJSON.message);
                    alert('更新失敗');
                }
            });
            changes = new FormData();
        }
    })



/**
 * 監聽edit mode是否開啟
 */
toggleSwitch.addEventListener("change", function (e) {
    if (e.target.checked) {

        for (let i = 0; i < dailyTexts.length; i++) {
            dailyTexts[i].style = "border-radius:40px 0 0 40px";
        }
        for (let j = 0; j < editInps.length; j++) {
            editInps[j].style = "display:flex";
        }
        addBtn.style.display = "flex";
    } else {

        for (let i = 0; i < dailyTexts.length; i++) {
            dailyTexts[i].style = "border-radius:40px";
        }
        for (let j = 0; j < editInps.length; j++) {
            editInps[j].style = "display:none";
        }
        addBtn.style.display = "none";
    }
})



/**
 * 監聽modal表單元素的變更事件
 */
let changes = new FormData();
modal.addEventListener('change', function (e) {
    const target = e.target;

    if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
        if (target.type === 'file') {
            changes.append(target.name, target.files[0]);
        } else {
            changes.append(target.name, target.value);
        }
    }
})



/**
 * get journal list AJAX
 */
function getJournals() {
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const monthStr = month < 10 ? '0' + month : month;



    $.ajax({
        url: "/api/journal/" + year + "/" + monthStr,
        method: "GET",
        success: function (res) {
            $("#main").empty();
            putInValues(res);
        },
        error: function (err) {
        }
    })
}



/**
 * create journal list
 * @param res json
 */
function putInValues(res) {
    $("#adYear").val(res.year);
    $("#hebrewYear").val(res.hebrewYear);
    $("#month").val(res.month);

    res.data.forEach(function (item, i) {

        // 建立fragment虛擬容器
        let fragment = document.createDocumentFragment();
        let dailySet = document.createElement('div');
        dailySet.className = "dailySet";
        fragment.appendChild(dailySet);

        let dailyCon = document.createElement('div');
        dailyCon.className = "dailyCon";
        dailyCon.id = "daily" + item.id;
        dailySet.appendChild(dailyCon);

        let date = document.createElement('p');;
        date.className = "date";
        date.textContent = item.date;
        dailyCon.appendChild(date);

        let dailyText = document.createElement('div');
        dailyText.className = "dailyText";
        dailyCon.appendChild(dailyText);

        let topic = document.createElement('p');
        topic.className = "topic";
        topic.textContent = item.title;
        dailyText.appendChild(topic);

        let text = document.createElement('p');
        text.className = "text";
        text.textContent = item.content;
        dailyText.appendChild(text);

        let editBtn = document.createElement('p');
        editBtn.className = "editBtn";
        editBtn.setAttribute("data-id", item.id);
        editBtn.setAttribute("type", "button");
        editBtn.setAttribute("data-bs-toggle", "modal");
        editBtn.setAttribute("data-bs-target", "#editModal");
        editBtn.textContent = "EDIT";
        dailyCon.appendChild(editBtn);

        // if has photos
        if (item.photos.length > 0) {
            let photos = document.createElement('div');
            photos.classList.add("photos", "d-flex");
            dailySet.appendChild(photos);

            item.photos.forEach(function (item, j) {
                let img = document.createElement('img');
                img.dataId = "photo" + item.photo_id;
                img.className = "photo";
                img.src = item.url;
                photos.appendChild(img);
            });

            let photoArrLength = item.photos.length;
            let remain = 5 - photoArrLength;
            for (let k = 0; k < remain; k++) {

                let space = document.createElement('div');

                if (k === remain - 1) {
                    space.innerHTML = "more";
                    space.className = "more";
                    photos.appendChild(space);
                } else {
                    space.className = "photo";
                    photos.appendChild(space);
                }
            }
        }

        let add = document.createElement('div');
        mainContent.appendChild(fragment);
    })
}



/**
 * get editing data
 */
function getEditModelData(journalId) {
    $.ajax({
        url: "api/journal/" + journalId,
        method: "GET",
        contentType: "json",
        success: function (res) {
            $("#modalBody input[name='id']").val(res[0].id);
            $("#modalBody input[name='date']").val(res[0].date);
            $("#modalBody input[name='title']").val(res[0].title);
            $("#modalBody input[name='link']").val(res[0].photosLink);
            $("#modalBody textarea[name='content']").val(res[0].content);
            $("#saveEdit").data('id', res[0].id);
            console.log(res[0].id);
            console.log($("#saveEdit").data('id'));

            res[0].photos.forEach(function (photo, i) {
                let name = 'des' + (i + 1);
                let imgId = 'photo' + (i + 1) + 'Pre';
                const des = document.querySelector(`input[name="${name}"]`);
                const img = document.getElementById(imgId);
                des.value = photo.description;
                img.src = photo.url;
            })
        },
        error: function (err) {
            alert(err.responseJSON.message);
        }
    })
}

/**
 * 上傳img同時預覽、可刪除
 */
function previewSelect(event) {
    const inputId = event.target.id;
    const input = document.getElementById(inputId);
    const file = input.files[0];
    const imgSet = input.parentNode.nextElementSibling;
    const imgPre = imgSet.querySelector('img');

    if (file) {
        imgPre.src = URL.createObjectURL(file);
    } else {
        imgPre.src = "";
    }
}
