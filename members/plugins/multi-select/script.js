const multiInput = document.querySelector('multi-input');

function checkForm()
{
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("family_lists").value = values;
    } else {
        alert("Error: Please select group member!");
        return false;
    }
    return true;
}

function checkEditForm()
{
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("family_lists").value = values;
    }
    return true;
}

function checkFriForm() {
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("friend_lists").value = values;
    } else {
        alert("Error: Please select group member!");
        return false;
    }
    return true;
}

function checkEditFriForm()
{
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("friend_lists").value = values;
    }
    return true;
}


document.querySelector('input').focus();
