
<script>
$('.recommend-slider').flickity({
    // options
    cellAlign: 'left',
    contain: true,
    prevNextButtons: false,
    pageDots: true,
    autoPlay: true
});


var isPercentage = true;
var prizes = [{
    text: "J2Team Security",
    percentpage: 0.24 // 24%
}, {
    text: "Áo thun J2Team",
    percentpage: 0.01 // 1%
}, {
    text: "Áo thun J2Team",
    percentpage: 0.01 // 1%
}, {
    text: "Vòng Tay J2Team",
    percentpage: 0.1 // 10%
}, {
    text: "J2Team Security",
    percentpage: 0.24 // 24%
}, {
    text: "Áo thun J2Team",
    percentpage: 0.01 // 1%
}, {
    text: "J2Team Security",
    percentpage: 0.24 // 24%
}, {
    text: "Good luck next time",
    percentpage: 0.1 // 60%
}];
document.addEventListener(
    "DOMContentLoaded",
    function () {
        hcLuckywheel.init({
            id: "luckywheel",
            config: function (callback) {
                callback &&
                    callback(prizes);
            },
            mode: "both",
            getPrize: function (callback) {
                var rand = randomIndex(prizes);
                var chances = rand;
                callback && callback([rand, chances]);
            },
            gotBack: function (data) {
                if (data == null) {
                    Swal.fire(
                        'Chương trình kết thúc',
                        'Đã hết phần thưởng',
                        'error'
                    )
                } else if (data == 'Good luck next time') {
                    Swal.fire(
                        "You didn't win the lottery",
                        data,
                        'error'
                    )
                } else {
                    Swal.fire(
                        'Won the prize',
                        data,
                        'success'
                    )
                    confetti();
                }
            }
        });
    },
    false
);

function randomIndex(prizes) {
    if (isPercentage) {
        var counter = 1;
        for (let i = 0; i < prizes.length; i++) {
            if (prizes[i].number == 0) {
                counter++
            }
        }
        if (counter == prizes.length) {
            return null
        }
        let rand = Math.random();
        let prizeIndex = null;
        console.log(rand);

        //START: create switch case 
        let result = 'switch (true) {\n';

        for (let i = 7; i >= 0; i--) {
            result += `  case rand < ${calculateCumulativePercent(i)}:\n`;
            result += `    prizeIndex = ${i};\n`;
            result += `    break;\n`;
        }

        result += `}`;
        eval(result);

        function calculateCumulativePercent(index) {
            let cumulativePercent = '';

            for (let j = 7; j >= index; j--) {
                cumulativePercent += `prizes[${j}].percentpage + `;
            }

            return cumulativePercent.slice(0, -3);
        }

        //END: create switch case 

        return prizeIndex;
    } else {
        var counter = 0;
        for (let i = 0; i < prizes.length; i++) {
            if (prizes[i].number == 0) {
                counter++
            }
        }
        if (counter == prizes.length) {
            return null
        }
        var rand = (Math.random() * (prizes.length)) >>> 0;
        return rand;
    }
}
</script>