var divs = [];

window.onload = function () {
    var html = document.getElementsByClassName('div');
    divs = [].slice.call(html);
    changeDiv(1);
};

function changeDiv(num) {
    var id = num - 1;
    divs.forEach(hideDivs);
    divs[id].style.display = 'inline';
}

function hideDivs(element) {
    element.style.display = 'none';
}

function checkNation(num) {
    var nation = document.getElementById('nation' + num),
        select = document.getElementById('nation_select' + num);
    nation.removeAttribute('disabled');
    if (select.value != 'Другое')
        nation.setAttribute('disabled', '');
}