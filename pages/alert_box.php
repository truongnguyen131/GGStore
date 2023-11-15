<form action="" method="post">
<div class="alert_box" id="alert_box" style="display: none;">
    <div class="alert_box_content">
        <span id="alert_title"></span>
        <div class="btnControl">
            <button name="btn_yes" id="btn_yes">Yes</button>
            <button name="btn_no" id="btn_no">No</button>
        </div>
    </div>
</div>
</form>
<script>
    const alert_box = document.getElementById('alert_box');
    const alert_content = document.querySelector('.alert_box_content');
    const alert_title = document.getElementById('alert_title');
    const btn_no = document.getElementById('btn_no');
    var content_title;
    function setting(content_title){
        alert_title.innerText = content_title;
    }
    btn_no.onclick = () =>{
        alert_box.style.display = 'none';
    }
    document.addEventListener('click', function(event) {
        const isClickInside = alert_content.contains(event.target);
        if (alert_box.style.display === 'block' && !isClickInside) {
            alert_box.style.display = 'none';
        }
    });
</script>