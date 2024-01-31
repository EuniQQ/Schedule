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
        var plusIconId = e.target.id;
        $("#addModal input[name='date']").val(plusIconId);
        openModalBtn.style.display = "none";
    })

    .on("click", ".closeModal", function (e) {
        addModal.style.display = "none";
    })

window.onclick = function (e) {
    if (e.target == addModal) {
        addModal.style.display = "none";
    }
}


stickerInp.onchange = evt => {
    const [file] = stickerInp.files
    if (file) {
        stickerPre.src = URL.createObjectURL(file)
    }
}
