var dailyTexts = document.getElementsByClassName("dailyText");
var editInps = document.getElementsByClassName("editBtn");
var toggleSwitch = document.getElementById("flexSwitchCheckDefault");
var mainContent = document.getElementById("main");


$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer' + getApiToken()
        }
    });

    getJournals();

})



toggleSwitch.addEventListener("change", function (e) {
    if (e.target.checked) {

        for (let i = 0; i < dailyTexts.length; i++) {
            dailyTexts[i].style = "border-radius:40px 0 0 40px";
        }
        for (let j = 0; j < editInps.length; j++) {
            editInps[j].style = "display:flex";
        }

    } else {

        for (let i = 0; i < dailyTexts.length; i++) {
            dailyTexts[i].style = "border-radius:40px";
        }
        for (let j = 0; j < editInps.length; j++) {
            editInps[j].style = "display:none";
        }
    }
})


/**
 * get journal list API
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
            console.log(res);
            putInValues(res);
        },
        error: function (error) {
        }
    })
}


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
        editBtn.id = "edit" + item.id;
        editBtn.textContent = "EDIT";
        dailyCon.appendChild(editBtn);

        // 如果有照片
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

                if (k === remain-1) {
                    space.innerHTML = "more";
                    space.className = "more";
                    photos.appendChild(space);
                } else {
                    space.className = "photo";
                    photos.appendChild(space);
                }
            }
        }

        mainContent.appendChild(fragment);
    })
}