
var cashEditBtn = document.getElementById('cashEditBtn');
var cashDelBtn = document.getElementById('cashDelBtn');
var cashDoneBtn = document.getElementById('cashDoneBtn');
var cardEditBtn = document.getElementById('cardEditBtn');
var cardDelBtn = document.getElementById('cardDelBtn');
var cardDoneBtn = document.getElementById('cardDoneBtn');

$(document).ready(function () {

})

$(document)
    .on('click', '#cashEditBtn', () => {
        this.cashToogleBtns();
    })

    .on('click', '#cashDoneBtn', () => {
        this.cashToogleBtns();
    })

    .on('click', '#cardEditBtn', () => {
        this.cardToogleBtns();
    })

    .on('click', '#cardDoneBtn', () => {
        this.cardToogleBtns();
    })


function cashToogleBtns() {
    const cashChboxs = document.querySelectorAll('.cashChbox');
    cashChboxs.forEach(item => {
        item.classList.toggle('d-none');
    });
    cashDelBtn.classList.toggle('d-none');
    cashDoneBtn.classList.toggle('d-none');
    cashEditBtn.classList.toggle('d-none');
}


function cardToogleBtns() {
    const cardChboxs = document.querySelectorAll('.cardChbox');
    cardChboxs.forEach(item => {
        item.classList.toggle('d-none');
    });
    cardDelBtn.classList.toggle('d-none');
    cardDoneBtn.classList.toggle('d-none');
    cardEditBtn.classList.toggle('d-none');
}