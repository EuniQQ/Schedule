var headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
};
var addModal = document.getElementById('addModal');
var openModalBtn = document.getElementsByClassName('plusIcon');
var closeBtn = document.getElementsByClassName('btn-close');
var calender = document.getElementsByClassName('calender');
var stickerPre = document.getElementById("stickerPre");
var mondalForm = document.getElementById("modalForm");
var modalTitle = document.getElementById("addModalLabel");
var saveBtn = document.getElementById("addModalSubmit");
var saveChgBtn = document.getElementById("editModalSubmit");
var stickerInp = document.getElementById("stickerInp");
var tagColorInp = document.querySelector("#addModal input[name='tag_color']")



/**
 * 監聽modal表單元素的變更事件
 */
let changes = {};
// let changes = [];
addModal.addEventListener('change', function (e) {
    const target = e.target;
    const id = saveChgBtn.getAttribute('data-id');
    if (target.tagName === 'INPUT') {
        if (target.type === 'file') {
            const file = target.files[0];
            const formData = new FormData();
            formData.append('sticker', file);
            changes['sticker'] = formData;
        } else {
            changes[target.name] = target.value;
        }
        console.log(target.name + " =" + changes[target.name]);
    }
})


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
    if (file) {
        stickerPre.src = URL.createObjectURL(file)
    }
}

/**
 * tagColor改變時
 */
let isTagColorModified = false;
tagColorInp.onchange = evt => {
    isTagColorModified = true;
}

$(document)
    .on("click", ".plusIcon", function (e) {
        addModal.style.display = "block";
        const plusIconId = e.target.id;
        const calenderId = e.target.getAttribute('data-id');
        $("#addModal input[name='date']").val(plusIconId);
        $("#addModal input[name='id']").val(calenderId);

        if (calenderId === '') {
            modalTitle.innerText = "新增行程";
            saveChgBtn.style.display = "none";
            saveBtn.style.display = "block";
            mondalForm.action = "/calender";
        } else {
            modalTitle.innerText = "修改行程";
            getModalContent(calenderId);
            saveBtn.style.display = "none";
            saveChgBtn.style.display = "block";
            saveChgBtn.setAttribute('data-id', calenderId);
            mondalForm.action = "/calender/" + calenderId;
        }
    })

    .on("click", ".closeModal", function (e) {
        addModal.style.display = "none";
    })

    .on("submit", "#modalForm", function (e) {
        // 防止表單自動提交
        e.preventDefault();

        if (isTagColorModified === 'false') {
            const tagColor = $("#addModal input[name='tag_color']").val();
            if (tagColor === '#000000') {
                $("#addModal input[name='tag_color']").val();
            }
        }

        // 繼續提交表單
        this.submit();
    })






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
            $("#addModal input[name='is_mc_start']").val(res.is_mc_start);
            $("#addModal input[name='is_mc_end']").val(res.is_mc_end);
            $("#addModal input[name='plan_time']").val(res.plan_time);
            $("#addModal input[name='plan']").val(res.plan);
            $("#addModal input[name='tag_from']").val(res.tag_from);
            $("#addModal input[name='tag_to']").val(res.tag_to);
            $("#addModal input[name='tag_title']").val(res.tag_title);
            $("#addModal input[name='tag_color']").val(res.tag_color);
            $("#addModal input[name='photos_link']").val(res.photos_link);
            $("#stickerPre").attr('src', res.sticker);
            $("#stickerPre").data('id', res.id);
        },
        error: function (error) {
            alert(error);
        }
    })
}
