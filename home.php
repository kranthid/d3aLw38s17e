<?php

?>
<script type="text/javascript">
    var lo = localStorage.getItem("dealuser");
    if(lo == "null" || lo == null){
       window.location.href ="index.php";
    }
</script>
<style type="text/css">
    .form-group {
        max-width: 400px !important;
    }
    #deal-form {
        margin-left: 20px;
    }
    .spacer-label{
        margin-right: 20px;
        font-weight: 100;
    }
</style>
<form id="deal-form">
        <label>DEAL TYPE</label><br/>
        <input type="radio" name="deals" value="deals" checked onclick="selectedRadioArray(this)" id="deals">
        <label class="spacer-label" for="deals">Deals</label>
        <input type="radio" name="deals" value="vouchers" id="vouchers" onclick="selectedRadioArray(this)">
        <label class="spacer-label" for="vouchers">Vouchers</label>
        <input type="radio" name="deals" value="freebies" id="freebies" onclick="selectedRadioArray(this)">
        <label class="spacer-label" for="freebies">Freebies</label>
        <!-- <input type="radio" name="deals" value="ask" id="ask" onclick="selectedRadioArray(this)">
        <label class="spacer-label" for="ask">Ask</label> -->
        <input type="radio" name="deals" value="competitions" id="competitions" onclick="selectedRadioArray(this)">
        <label class="spacer-label" for="competitions">Competitions</label>
        <br><br>
        <div id="customFormElements">
        </div>

</form>