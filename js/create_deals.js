var deal_ary = new Array("DEAL URL", "TITLE", "PRICE", "TOPIC", "DETAILS", "ENTER IMAGE URL", "TAGS", "START DATE", "END DATE");
var voucher_array = new Array("DEAL URL", "TITLE", "TOPIC", "Discount ", "code", "TAGS", "MINIMUM SPEND", "APPLIES TO", "INSTRUCTIONS" ,"START DATE", "END DATE");
var freebies_array = new Array("DEAL URL", "TITLE", "TOPIC", "DETAILS", "ENTER IMAGE URL", "TAGS", "START DATE", "END DATE");
var competitions_array = new Array("DEAL URL", "TITLE", "TOPIC", "DETAILS", "PRIZE", "PERIOD", "ENTER IMAGE URL", "TAGS", "VALUE", "RULES", "LINK TO RULES", "START DATE", "END DATE");
        
var selectedRadioArray = function (elem) {
    var selectedRadioId = elem.id;
    document.getElementById('customFormElements').innerHTML = "";
    switch (selectedRadioId) {
    case 'deals':
        createFormElements(deal_ary);
        break;
    case 'vouchers':
        createFormElements(voucher_array);
        break;
    case 'freebies':
        createFormElements(freebies_array);
        break;
    case 'competitions':
        createFormElements(competitions_array);
        break;
    }
}
$( document ).ready(function() {
    document.getElementById('deals').checked = true;
  createFormElements(deal_ary);
});

function createFormElements(formElement) {
    var append_to = document.getElementById('customFormElements');
    for (var i = 0; i < formElement.length; i++) {
        var divElem = document.createElement('div');
        divElem.className = "form-group";
        var labelElem = document.createElement('label');
        labelElem.innerHTML = formElement[i];
        labelElem.setAttribute("for", formElement[i]);

        var innerDivElem = document.createElement('div');
        innerDivElem.className = "input-group";
        var inputElem = document.createElement('input');
        inputElem.type = "text";
        inputElem.className = "form-control";
        inputElem.setAttribute("id", formElement[i]);
        inputElem.setAttribute("placeholder", "Enter " + formElement[i]);

        var parentSpanElem = document.createElement('span');
        parentSpanElem.className = "input-group-addon";

        var childSpanElem = document.createElement('span');
        childSpanElem.className = "glyphicon glyphicon-asterisk";


        divElem.appendChild(labelElem);
        innerDivElem.appendChild(inputElem);
        parentSpanElem.appendChild(childSpanElem);
        innerDivElem.appendChild(parentSpanElem);
        divElem.appendChild(innerDivElem);
        append_to.appendChild(divElem);
    }
}









function clearFunction(){
    document.getElementById("createPanel").reset();
}

function createDeal(){
    var objData = {
                    "Id": $('#Id').val(),
                    "user_id": $('#uid').val(),
                    "deal_url":  $('#url').val(),
                    "title":  $('#title').val(),
                    "deal_price":  $('#deal_price').val(),
                    "deal_availablity":  $('#deal_availablity').val(),
                    "city_postcode": $('#city_postcode').val(),
                    "deal_category": $('#category').val(),
                    "deal_topic": $('#topic').val(),
                    "discount": $('#discount').val(),
                    "discount_code": $('#discount_code').val(),
                    "detail": $('#detail').val(),
                    "prize": $('#prize').val(),
                    "period": $('#period').val(),
                    "deal_image": $('#image').val(),
                    "deal_image_url":$('#image_url').val(),
                    "tags": $('#tags').val(),
                    "deal_rule": $('#rule').val(),
                    "link_to_rule": $('#link_rule').val(),
                    "apply_to": $('#apply_to').val(),
                    "report": $('#report').val(),
                    "deal_like": $('#like').val(),
                    "deal_dislike": $('#dislike').val(),
                    "start_date": $('#sdate').val(),
                    "end_date": $('#edate').val(),
                    "status": $('#status').val()
                };
    var phpPostUrl = "http://localhost/php/Deal_market/public/api/deal/create"
    $.ajax({
        url : phpPostUrl,
        type: "POST",
        data : JSON.stringify(objData),
        success: function(data, textStatus, jqXHR)
        {
            console.log("Data sent to the server >>>>>>>>>>>>>>>>",data);
            alert("You have created the deal successfully")
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("Error while sending data to the server>>>>>>>>>>>>>>>>",errorThrown)
        }
    });

}