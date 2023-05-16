var kurs = document.getElementById('targetCurr1').value
function calculateFrom(ilosc) {
    document.getElementById('targetCurr2').value = (ilosc/kurs).toFixed(4)
}

function calculateTo(ilosc) {
    document.getElementById('targetCurr1').value = (ilosc*kurs).toFixed(4)
}

calculateTo(document.getElementById('sourceCurr1').value)