var dailyTexts = document.getElementsByClassName("dailyText");
var editInps = document.getElementsByClassName("editBtn");
var toggleSwitch = document.getElementById("flexSwitchCheckDefault");

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


function getJournals() {
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const monthStr = month < 10 ? '0' + month : month;

    $.ajax({
        url: "/api/journal/" + year + "/" + monthStr,
        method: "GET",
        success: function (res) {

        },
        error: function (error) {

        }
    })
}


