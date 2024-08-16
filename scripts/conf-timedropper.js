var formatoHoras = ["HH:mm", "hh:mm", "h:mm A"];

function initTimeDropper(arrayIds, stepts = 5, formato = 0) {
    arrayIds.forEach(element => {
        $("#" + element).timeDropper({
            format: formatoHoras[formato],
            autoswitch: true,
            meridians: true,
            mousewheel: false,
            setCurrentTime: true,
            minutesSteps: stepts
        });
    });
}
