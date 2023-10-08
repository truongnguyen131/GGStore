function pre_next(choose) {
    var paginationItems = document.getElementsByClassName("paginate_button page-item");
    for (var i = 1; i < paginationItems.length - 1; i++) {
        if (paginationItems[i].classList.contains("active")) {
            if (i != 1 && choose == 0) {
                search(i - 1);
            }
            if (i != choose && choose != 0) {
                search(i + 1);
            }
        }
    }
}

