
function toggleMenu() {

    var menuItems = $('.menu-item');

    if (menuItems.length > 0) {

        if ($(menuItems[0]).css('display') == 'none') {
            menuItems.show();
        } else {
            menuItems.hide();
        }
    }

}
