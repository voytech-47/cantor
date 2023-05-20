function checkClick(id) {
    document.querySelector('input[name="nrtabeli"]').value = "DESC";
    document.querySelector('input[name="data"]').value = "DESC";
    document.querySelector('input[name="kurs"]').value = "DESC";
    document.querySelector('input[name="'+id+'"').value = "ASC";

    // document.getElementById('form').submit()
}