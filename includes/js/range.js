let rangeInput = document.querySelectorAll('.range-input input');
let rangeText = document.querySelectorAll('.range-text div');
let progress = document.querySelector('.progres');
let priceMax = rangeInput[0].max;
let priceGap = 1;

rangeInput.forEach(input => {
    input.addEventListener('input', (event) => {
        let minVal = parseInt(rangeInput[0].value);
        let maxVal = parseInt(rangeInput[1].value);

        if (maxVal - minVal < priceGap) {
            if (event.target.className === 'range-min') {
                minVal = rangeInput[0].value = maxVal - priceGap;
            } else {
                maxVal = rangeInput[1].value = minVal + priceGap;
            }
        }

        let positionMin = (minVal / priceMax) * 100;
        let positionMax = 100 - ((maxVal / priceMax) * 100);
        progress.style.left = positionMin + '%';
        progress.style.right = positionMax + '%';
        rangeText[0].style.left = positionMin + '%';
        rangeText[1].style.right = positionMax + '%';
        rangeText[0].innerText = minVal.toLocaleString();
        rangeText[1].innerText = maxVal.toLocaleString();
    })
})