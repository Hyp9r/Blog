function copyDataToInputTag(counter){
    var aTag = document.getElementsByClassName('search-item')[counter];
    var searchInput = document.getElementById('search_text').value = aTag.childNodes[1].textContent;
    var searchButton = document.getElementById('search_submit').click();
}