function copyDataToInputTag(counter){
    var aTag = document.getElementsByClassName('search-item')[counter];
    var text = aTag.childNodes[1].textContent.trim();
    var searchInput = document.getElementById('search_text').value = text;
    var searchButton = document.getElementById('search_submit').click();
}