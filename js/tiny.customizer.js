function initTiny() {
    let button = $('<div title="criar botão" role="toolbar" data-alloy-tabstop="true" tabindex="-1" class="tox-toolbar__group"><button aria-label="criar botão" title="criar botão" type="button" tabindex="-1" class="tox-tbtn" id="create-button-btn"><span class="fas fa-plus-square"></span></button></div>')
    $('div.tox-toolbar').append(button);

    $('#create-button-btn').click(function() {
        let btn = $(document.createElement('a')).attr('href', '#').css({
            padding: '10px 20px',
            color: 'white',
            backgroundColor: '#212529'
        }).text('[]');
        $('#tinymce').append(btn);
    });
}

$(function() {
    setTimeout(initTiny, 1000);
});  