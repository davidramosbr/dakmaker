$(document).ready(function() {
    setTimeout(function(){
        $('#preloader').fadeOut();
    }, 500);
    
    function initSubmenus(container) {
        $(container + ' .menu-submenus').each(function() {
            const height = $(this).outerHeight();
            $(this).attr('data-height', height);
            $(this).css('height', '0');
        });

        const savedStates = JSON.parse(localStorage.getItem(container + 'MenuStates')) || [];
        $(container + ' .menu-submenus').each(function(index) {
            const height = savedStates[index];
            if (height !== undefined) {
                $(this).css('height', height);
            }
        });

        $(window).on('beforeunload', function() {
            const states = [];
            $(container + ' .menu-submenus').each(function(index) {
                const currentHeight = $(this).css('height');
                states[index] = currentHeight === '0px' ? 0 : $(this).attr('data-height');
            });
            localStorage.setItem(container + 'MenuStates', JSON.stringify(states));
        });

        $(container + ' .menu-item').on('click', function() {
            const $submenu = $(this).parent().find('.menu-submenus');
            const currentHeight = $submenu.css('height');
            if (currentHeight === '0px') {
                const originalHeight = $submenu.attr('data-height');
                $submenu.css('height', originalHeight);
            } else {
                $submenu.css('height', '0');
            }
        });
    }

    initSubmenus('.mobile-menu');
    initSubmenus('.menu-container');
});
