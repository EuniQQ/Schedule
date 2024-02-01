var headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
};
var addModal = document.getElementById('addModal');
var openModalBtn = document.getElementsByClassName('plusIcon');
var closeBtn = document.getElementsByClassName('btn-close');
var calender = document.getElementsByClassName('calender');
var stickerInp = document.getElementById("stickerInp");
var stickerPre = document.getElementById("stickerPre");


$(document)
    .on("click", ".plusIcon", function (e) {
        addModal.style.display = "block";
        // openModalBtn.style.display = "none";
        const plusIconId = e.target.id;
        const calenderId = e.target.getAttribute('data-id');
        const ModalTitle = document.getElementById
            ("addModalLabel");
        $("#addModal input[name='date']").val(plusIconId);
        $("#addModal input[name='id']").val(calenderId);
        console.log("plusIconId = " + plusIconId);
        console.log("calenderId = " + calenderId);
        if (calenderId === '') {
            ModalTitle.innerText = "新增行程";
        } else {
            ModalTitle.innerText = "修改行程";
            getModalContent(calenderId);
        }
    })

    .on("click", ".closeModal", function (e) {
        addModal.style.display = "none";
    })


function getModalContent(calenderId) {
    $.ajax({
        url: "/api/daylySchedule/" + calenderId,
        method: "GET",
        success: function (res) {
            $("#addModal input[name='birthday_person']").val(res.birthday_person);
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


// 點擊modal以外處，關閉modal
window.onclick = evt => {
    if (evt.target == addModal) {
        addModal.style.display = "none";
    }
}

// 上傳sticker同時預覽
stickerInp.onchange = evt => {
    const [file] = stickerInp.files
    if (file) {
        stickerPre.src = URL.createObjectURL(file)
    }
}
