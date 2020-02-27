function checkFamForm()
{
    const multiInput = document.querySelector('multi-input');
    if (multiInput !== null) {
        if (multiInput.getValues().length > 0) {
            var values = multiInput.getValues().join(',');
            console.log('selected group members: ', values);
            document.getElementById("family_lists").value = values;
        } else {
            alert("Error: Please select group member!");
            return false;
        }
        return true;
    } else {
        alert('Please add family member');
        return false;
    }
}

function checkEditForm()
{
    const multiInput = document.querySelector('multi-input');
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("family_lists").value = values;
    }
    return true;
}

function checkFriForm() {
    const multiInput = document.querySelector('multi-input');
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
    const multiInput = document.querySelector('multi-input');
    if (multiInput.getValues().length > 0) {
        var values = multiInput.getValues().join(',');
        console.log('selected group members: ', values);
        document.getElementById("friend_lists").value = values;
    }
    return true;
}


document.querySelector('input').focus();
