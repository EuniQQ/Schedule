var dailyTexts = document.getElementsByClassName("dailyText");

var toggleSwitch = document.getElementById("flexSwitchCheckDefault");

var searchKey = document.getElementById("searchKey");

var saveAddBtn = document.getElementById("saveAdd");

var saveChgBtn = document.getElementById("saveEdit");

var mainContent = document.getElementById("main");

var addBtn = document.getElementById("addBtn");

var editInps = document.getElementsByClassName("editBtn");

var modal = document.getElementById("editModal");

var modalTitle = document.getElementById("editModalLabel");

var modalImgPres = document.getElementsByClassName("photoPre");

var yearSel = document.getElementById('yearSel');

var jourMonthSel = document.getElementById("searchMonth");

var mainImg = document.getElementById("mainImg");

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
    .on("click", "#addBtn,#mainImg", function (e) {
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
     * clear input val when close modal
     */
    .on("click", ".closeEdit", function () {
        clearEditModalInp();
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
                getJournals();
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
     * delete one piece of img of edit modal
     */
    .on("click", ".delMoImgBtn", function (e) {
        let imgId = e.target.getAttribute("data-id");
        let journalId = e.target.getAttribute("data-journalId");

        $.ajax({
            url: "/api/journal/singleImg/" + imgId,
            type: "POST",
            data: {
                _method: "DELETE",
                journalId: journalId
            },
            success: function (res) {
                clearEditModalInp();
                putValIntoEditModal(res);
                getJournals();
            },
            error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
    })



    .on("click", "#del", function (e) {
        const id = $("#del").data('id');

        $.ajax({
            url: "/api/journal/" + id,
            type: "POST",
            data: {
                _method: "DELETE"
            },
            success: function (res) {
                alert(res.message);
                $("#editModal").hide();
                $(".modal-backdrop").remove();
                getJournals();
            },
            error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
    })



    .on("click", ".photo", function (e) {
        let imgId = e.target.dataId;
        console.log(imgId)
        $.ajax({
            url: "/api/journal/photoModal/" + imgId,
            method: "GET",
            success: function (res) {

                const modal = document.createElement('div');
                modal.className = 'photoModal';
                const img = document.createElement('img');
                img.src = e.target.src;
                img.className = 'modal-content';
                const p = document.createElement('p');
                p.className = 'photoModalP';
                p.innerText = res.description;
                modal.appendChild(img);
                modal.appendChild(p);
                document.body.appendChild(modal);

                modal.onclick = function () {
                    document.body.appendChild(modal);
                }

                document.body.onclick = function () {
                    document.body.removeChild(modal);
                }

                modal.onclick = function () {
                    document.body.removeChild(modal);
                }
            },
            error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
    })


    .on("click", "#searchIcon", function () {
        let selectedYear = yearSel.value;
        let selectedMonth = jourMonthSel.value;

        $.ajax({
            url: "/api/journal/" + selectedYear + "/" + selectedMonth,
            method: "GET",
            success: function (res) {
                $("#main").empty();
                $("#adYear").text(res.year);
                $("#hebrewYear").text(res.hebrewYear);
                $("#month").text(res.month);
                putInValues(res);
            },
            error: function (err) {
                console.log(err.responseJSON.message);
            }
        })
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
 * 監聽search欄位
 */
searchKey.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        // 防止預設的enter鍵行為,如換行
        e.preventDefault();

        if (e.target.value !== '') {
            let keyword = e.target.value;
            $.ajax({
                url: "/api/journal/search",
                method: "GET",
                data: {
                    "keyword": keyword
                },
                dataType: "json",
                success: function (res) {
                    // while迴圈逐次刪除第一個節點
                    while (mainContent.hasChildNodes()) {
                        mainContent.removeChild(mainContent.firstChild);
                    }
                    putInValues(res);
                },
                error: function (err) {
                }
            })
        } else {
            getJournals();
        }
    }
});



/**
 * 監聽空白頁圖示
 */
mainImg.addEventListener('mouseover', function (e) {
    e.target.src = "/storage/img/go.png";
})

mainImg.addEventListener('mouseout', function (e) {
    e.target.src = "/storage/img/howstoday.png";
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
            $("#adYear").text(res.year);
            $("#hebrewYear").text(res.hebrewYear);
            $("#month").text(res.month);
            makeYearListOpt(res);
            putInValues(res);
        },
        error: function (err) {
            console.log(err.responseJSON.message);
        }
    })
}



/**
 * create journal list
 * @param res json
 */
function putInValues(res) {

    if (res.data.length > 0) {
        mainImg.style = "display:none";
    } else {
        mainImg.style = "display:block";
    }

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

        let date = document.createElement('p');
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
                // img.dataId = "photo" + item.photo_id;
                img.dataId = item.photo_id;
                img.className = "photo";
                img.src = item.url;

                photos.appendChild(img);
            });

            let photoArrLength = item.photos.length;
            let remain = 5 - photoArrLength;
            for (let k = 0; k < remain; k++) {

                let space = document.createElement('div');
                if (k === remain - 1) {
                    space.className = "more";
                    if (item.photosLink !== null) {
                        let moreATag = document.createElement('a');
                        moreATag.href = item.photosLink;
                        moreATag.innerHTML = "more";
                        moreATag.target = "_blank";
                        space.appendChild(moreATag);
                    } else {
                        space.innerHTML = "more";
                    }
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
        url: "/api/journal/" + journalId,
        method: "GET",
        contentType: "json",
        success: function (res) {
            putValIntoEditModal(res);
        },
        error: function (err) {
            alert(err.responseJSON.message);
        }
    })
}



/**
 * 填入edit modal的欄位值
 */
function putValIntoEditModal(res) {
    $("#modalBody input[name='id']").val(res[0].id);
    $("#modalBody input[name='date']").val(res[0].date);
    $("#modalBody input[name='title']").val(res[0].title);
    $("#modalBody input[name='link']").val(res[0].photosLink);
    $("#modalBody textarea[name='content']").val(res[0].content);
    $("#saveEdit").data('id', res[0].id);
    $("#del").data('id', res[0].id);

    res[0].photos.forEach(function (photo, i) {
        let name = 'des' + (i + 1);
        let imgId = 'photo' + (i + 1) + 'Pre';
        const des = document.querySelector(`input[name="${name}"]`);
        const img = document.getElementById(imgId);
        des.value = photo.description;
        img.src = photo.url;

        // 建立delImg icon在imgPre後面
        const icon = document.createElement('i');
        icon.classList.add('delMoImgBtn', 'fa-solid',
            'fa-circle-minus', 'fa-2xl');
        icon.setAttribute('data-id', photo.photo_id);
        icon.setAttribute('data-journalId', res[0].id);
        // insertAdjacentElement()可將元素插入另一元素的指定位置
        img.insertAdjacentElement('afterend', icon);
    })
}



/**
 * 將edit modal所有欄位清空
 */
function clearEditModalInp() {
    const textInps = document.querySelectorAll('input[type="text"]');
    const uploadInps = document.querySelectorAll('input[type="upload"]');
    const imgPres = document.querySelectorAll(".imgSet img");
    const hiddenInp = document.querySelector('input[type="hidden"]');
    const dateInp = document.querySelector('input[type="date"]');
    const contentInp = document.getElementById("moContent");
    const linkInp = document.querySelector('input[type="url"]');
    const delImgBtns = document.querySelectorAll('.delMoImgBtn');
    textInps.forEach(textInp => {
        textInp.value = '';
    });

    uploadInps.forEach(uploadInp => {
        uploadInp = '';
    });

    imgPres.forEach(img => {
        img.src = '';
    })

    delImgBtns.forEach(delImgBtn => {
        delImgBtn.remove();
    })

    hiddenInp.value = dateInp.value = contentInp.value = linkInp.value = '';
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

