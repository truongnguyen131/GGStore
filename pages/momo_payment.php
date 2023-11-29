<a href="" id="QR_MOMO"><button>Payment by QR Code MoMo</button></a>
<a href="" id="QR_ATM"><button>Payment by ATM MoMo</button></a>

<script>
    let selectedPrice = sessionStorage.getItem('price');
    var url_QR_MOMO = "./momo_QR_process.php?amount="+selectedPrice; 
    var url_ATM_MOMO = "./momo_ATM_process.php?amount="+selectedPrice; 
    document.getElementById("QR_MOMO").href = url_QR_MOMO;
    document.getElementById("QR_ATM").href = url_ATM_MOMO;
</script>