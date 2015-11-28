<?php

?>
<script type="text/javascript">
    var lo = localStorage.getItem("dealuser");
    alert(lo);
    if(lo == "null" || lo == null){
       window.location.href ="index.php";
    }
</script>

<form>
        <input type="radio" name="deals" value="deals" checked onclick="selectedRadioArray(this)" id="deals">Deals

        <input type="radio" name="deals" value="vouchers" id="vouchers" onclick="selectedRadioArray(this)">Vouchers
        <input type="radio" name="deals" value="freebies" id="freebies" onclick="selectedRadioArray(this)">Freebies
        <input type="radio" name="deals" value="ask" id="ask" onclick="selectedRadioArray(this)">Ask
        <input type="radio" name="deals" value="competitions" id="competitions" onclick="selectedRadioArray(this)">Competitions

        <div id="customFormElements">

        </div>

</form>