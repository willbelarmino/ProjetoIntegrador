function showErrorNotification(message){
    $.notify({
        icon: "notifications",
        message: message
    },{
        type: "danger",
        timer: 3000,
    });
}

function showSucessNotification(message){
    $.notify({
        icon: "notifications",
        message: message
    },{
        type: "success",
        timer: 3000,
    });
}