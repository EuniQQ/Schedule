
var addModal = document.getElementById('addModal');
var openModalBtn = document.getElementsByClassName('plusIcon');
var closeBtn = document.getElementsByClassName('btn-close');
var calender = document.getElementsByClassName('calender');
$(document).on("click", ".plusIcon", function (e) {
    addModal.style.display = "block";
    openModalBtn.style.display = "none";
    if (e.target == addModal) {
        addModal.style.display = "none";
    }
})
    .on("click", ".closeModal", function (e) {
        addModal.style.display = "none";
    })
